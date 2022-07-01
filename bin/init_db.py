change_log = """
##########################################
2021-03-25, apply locale, bug fix

#######################################
"""

import os, sys, time
from math import floor
import pymysql
import threading
import sqlite3
import locale

from chkLic import  getMac

if getMac() == '525400C9FE37':
    print ("This program cannot be run in Server base, Critical Error")
    # sys.exit()

# LANG = os.getenv('LANG')
# print (LANG)
LOCALE = locale.getdefaultlocale()
print (LOCALE)

rootdir = os.path.dirname(os.path.abspath(sys.argv[0]))
rootdir = os.path.dirname(rootdir)

default_db_file = ""
if os.path.isfile(rootdir + "/bin/db_ref"):
    default_db_file = rootdir + "/bin/db_ref"

print (rootdir)
print (default_db_file)

# sys.exit()


def is_alive_mysqld():
    if os.name == 'nt':
        # a = os.popen('tasklist /fi "imagename eq mysqld.exe" /nh ')
        a = os.popen('wmic process where "name=\'mysqld.exe\'" get ProcessID, ExecutablePath')
    elif os.name == 'posix':
        a = os.popen('ps -ef | grep mysqld ')
    p = a.read()
    for line in p.splitlines() :
        if line.find('grep ') >=0 or line.find('_safe') >=0 :
            continue
        for i in range(10):
            line = line.replace("  "," ")
        line = line.strip()
        if line.find('mysql') >=0:
            if os.name == 'nt':
                return line.split(" ")[0]
            elif os.name == 'posix':
                line = line.split("--")[0]
                return line.strip().split(" ")[-1]

    return False


def stop_mysqld():
    if os.name == 'nt':
        os.system("taskkill /F /IM mysqld.exe > nul")
    elif os.name == 'posix':
        pass


def start_mysqld():
    if os.name == 'nt' :
        exec_path = rootdir + "/MariaDB/bin/mysqld.exe"
        if os.path.isfile(exec_path) :
            os.system("start " + exec_path)
            print ("starting mysqld: %s" %exec_path)
            time.sleep(5)
            return True
        else :
            print ("%s is not exists!!" %exec_path)
            return False
    
    elif os.name == 'posix':
        pass # because of root auth.
    

if os.name == 'nt':
    from tkinter import *
    from tkinter import ttk
    from tkinter import filedialog
    import tkinter.messagebox as tkmsg

    def browse_dir():
        fname = filedialog.askopenfilename(filetypes=(("All files", "*"), ("Sql", "*.sql")))
        if fname:
            entDiag.delete(0,'end')
            entDiag.insert(0, fname)

    def message(str, type='info') :
        tx.insert("end", str + "\n")
        if type == "error" :
            s = tx.index("end").split('.')
            st = "%d.0" %(int(s[0])-2)
            tt = "%d.0" %(int(s[0])-1)
        #		print st	
            tx.tag_add("tag", st , tt)
            tx.tag_config("tag", foreground="red")
        try:
            tx.see('end')
        except:
            pass
    
    def  init_DB():
        if LOCALE[0] == 'zh_CN':
            rs = tkmsg.askokcancel('确认',"初始化 进行 \n注意所有资料永久删除!!")
        else :
            rs = tkmsg.askokcancel('Confirm',"Are you sure to initiate database \n data will be lost permantly!!")
        if rs :
            adminid = 'root'
            adminpass = 'rootpass'
            create_db_user('ct_user', '13579', adminid, adminpass, host='localhost')
            create_db_user('rt_user', '13579', adminid, adminpass, host='%')
            make_log_db(rootdir + "/bin/log/log.db")
            make_config_db(rootdir + "/bin/param.db")
            fname = entDiag.get()
            t = threading.Thread(target = init_db, args=(fname,))
            t.start()
            btn_init['state'] = "disabled"
        


def init_db(fname='db_ref', db_name='cnt_demo', rootid='root', rootpass ='rootpass' ):
    try:
        dbconn0 = pymysql.connect(host = 'localhost', user = str(rootid), password = str(rootpass), charset = 'utf8')
        # dbconn0 = pymysql.connect(host = str(CFG('MYSQL','HOST')), user = 'root', password = 'rootpass', db = str(CFG('MYSQL', 'DB')), charset = str(CFG('MYSQL', 'CHARSET')))
    except pymysql.err.OperationalError as e:
        print (e)
     
        # not running
        # (2003, "Can't connect to MySQL server on 'localhost' ([WinError 10061] No connection could be made because the target machine actively refused it)")
        # running but username, password not correct
        # (1045, "Access denied for user 'ct_user'@'localhost' (using password: YES)")

        if str(e).find('2003') >=0:
            print ("Run Mysqld")
        elif str(e).find('1045') >=0:
            print ("check username and password for mysqld")
        else :
            print(e)
        dbconn0.close()
        exit()

    cur = dbconn0.cursor()
    sq = "show databases where `Database` not like 'mysql' and `Database` not like '%_schema' and `Database` not like 'test'"
    cur.execute(sq)
    rows = cur.fetchall()
    db_names = [row[0] for row in rows]
    print(db_names)

    with open(fname, 'r', encoding='utf-8') as f:
        body = f.read()
    
    lines = body.splitlines()

    prog['maximum'] = len(lines)
    sq =''
    for i, line in enumerate(lines):
        line = line.strip()
        if not line or line[:2] == '--':
            continue
        
        sq += line
        if line[-1] == ';' :
            c = cur.execute(sq)
            # print (sq)
            sq = ''
        
        prog["value"] = i    
        if os.name == 'nt' :
            message(sq)
            prog.update()
        
        else :
            progress()

    if os.name == 'nt':
        message ("Completed")
        btn_init['state'] = "normal"
    
    elif os.name=='posix':
        prog["value"] = i+1
        progress()
        print ("Completed, \n\n")



    dbconn0.commit()
    cur.close()
    dbconn0.close()
    return True

def create_db_user(id, passwd, adminid, adminpasswd, host='localhost'):
    dbconn0 = pymysql.connect(host = 'localhost', user = str(adminid), password = str(adminpasswd), charset = 'utf8')
    cur = dbconn0.cursor()
    sq = "select user from mysql.user where user='%s' and host='%s'" %(id, host)
    cur.execute(sq)
    rows = cur.fetchall()
    print (rows)
    if rows:
        # sq = "alter user '%s'@'localhost' IDENTIFIED BY '%s'" %(id, passwd)
        sq = "UPDATE mysql.user SET authentication_string = PASSWORD('%s') WHERE User = '%s' AND Host = '%s'" %(passwd, id, host)
    else:
        sq = "create user '%s'@'%s' IDENTIFIED BY '%s'" %(id, host, passwd)
        
    
    print (sq)
    try:
        cur.execute(sq)
    except Exception as e:
        print (e) 

    if host == 'localhost' :
        sq = "grant insert, select, update, delete, alter on common.* to '%s'@'%s' " %(id, host)
        cur.execute(sq)
        sq = "grant insert, select, update, delete, alter on cnt_demo.* to '%s'@'%s'" %(id,host)
        cur.execute(sq)
    else :
        sq = "grant select on common.* to '%s'@'%s' " %(id,host)
        cur.execute(sq)
        sq = "grant select on cnt_demo.* to '%s'@'%s'" %(id,host)
        cur.execute(sq)
    
    sq = "flush privileges"
    try:
        cur.execute(sq)
    except:
        print ("flush privileges Error \n mysqlcheck -r mysql tables_priv -u root -p \n mysqlcheck -u root -p --auto-repair -c -o --all-databases")

    dbconn0.commit()
    cur.close()
    dbconn0.close()


def make_log_db(log_file = 'log/log.db', log_table='log'): 
    print ("Initializing LOG DB .", end='')
    dbsqcon = sqlite3.connect(log_file)
    cur = dbsqcon.cursor()
    sq = "DROP TABLE IF EXISTS " + log_table
    cur.execute(sq)
    # dbsqcon.commit()

    print (".", end='')
    sq = "CREATE TABLE " + log_table + "(Created text, Name text, LogLevel text, LogLevelName text, Message text, Module text, FuncName text, LineNo text, Exception text, Process text, Thread text, ThreadName text)"
    cur.execute(sq)
    # dbsqcon.commit()
    print (".", end='')
    sq = "VACUUM "
    cur.execute(sq)
    dbsqcon.commit()
    print (".", end='')

    cur.close()
    dbsqcon.close()

    print ("  complete ")


#### SQLITE 3, config, param, info
def make_config_db(fname="param.db", table = "config_tbl"):
    print ("Initializing CONFIG DB .", end='')
    dbsqcon = sqlite3.connect(fname)
    cur = dbsqcon.cursor()
    sq = "DROP TABLE IF EXISTS " + table
    cur.execute(sq)
    sq = """CREATE TABLE IF NOT EXISTS """ + table + """(
        prino INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
        SECTION TEXT,
        entryName TEXT,
        entryValue TEXT,
        description TEXT,
        datatype TEXT default 'sz',
        readonly INTEGER default 0,
        option TEXT
        )"""
    cur.execute(sq)
    dbsqcon.commit()
    
    config_rows = [
        ("ROOT",    "DOCUMENT_TITLE",   "BUSINESS INTELLIGENCE",    "Title of Platform", "", 0, "sz"),
        ("ROOT",    "HOST_TITLE",       "Business Intelligence",    "Title of web page", "", 0, "sz"),
        ("ROOT",    "LOGO_PATH",        "/libs/pfh_logo.png",       "path of log file, png, jpg etc", "", 0, "sz"),
        ("ROOT",    "DEVELOPER",        "hanskim",                  "Name of developer", "", 1, "sz"),
        ("ROOT",    "APPLICATION",      "PEOPLE",                   "Application of this platform, Traffic is not available at the time.", "PEOPLE,TRAFFIC", 1, "select"),
        ("SERVICE", "MODE",             "ACTIVE",                   "Mode of Counting Service, TLSS: Hosted service, ACTIVE: Platform polling each device in Local area network, EVENT: each device push data to platform", "TLSS,ACTIVE", 0, "select"),
        ("SERVICE", "COUNT_EVENT",      "NO",                      "realtime push counting servery", "NO,HTTP,TCP", 0, "select"),
        ("SERVICE", "COUNTING",         "yes",  "enable Counting service, default check", "yes,no", 0, "yesno"),
        ("SERVICE", "FACE",             "no",   "Enable face detection,age and gender, need face detection camera and face++ account, default un-check", "yes,no", 0, "yesno"),
        ("SERVICE", "MAC_SNIFF",        "no",   "Enable Mac sniff", "default un-check""	yes,no", 0, "yesno"),
        ("SERVICE", "SNAPSHOT",         "no",   "Enable Snapshot recording, every 10 minutee", "yes,no", 0, "yesno"),
        ("SERVICE", "PROBE_INTERVAL",   30,     "time(secs) of proble", "30|600", 0, "int"),
        ("SERVICE", "ROOT_DIR",         "/var/www/", "Root dictory of platform", "", 0, "sz"),
        ("SERVICE", "START_ON_BOOT",    "yes",  "Auto start on boot on windows, already enabled on linux system", "yes,no", 0 , "yesno"),
        ("MYSQL",   "HOST",             "localhost", "Maria DB host, default localhost if platform is installed on the machine", "", 0, "sz"),
        ("MYSQL",   "USER",             "ct_user", "Mysql sql user, not root or admin ID", "", 0, "sz"),
        ("MYSQL",   "PASSWORD",         "13579", "Mysql password for user ID", "", 0, "sz"),
        ("MYSQL",   "DB",               "common", "Mysql Common db name", "", 1, "sz"),
        ("MYSQL",   "CHARSET", "utf8", "Charset of Mysql, default utf-8", "", 1, "sz"),
        ("MYSQL",   "RECYCLING_TIMESTAMP", "30days", "Recycling time(days) of Common database, erased if recycling time pass over and already data transfer to custom database.", "30days,90days,180days", 0, "select"),
        ("PORT",    "TLSS",	        5000,	"Port number of Hosted service, only available when TLSS service enabled.", "", 0, "port"),
        ("PORT",    "COUNT_EVENT",	5030,	"Port number of Count event, only available when COUNT EVENT enabled.", "", 0, "port"),
        ("PORT",    "MACSNIFF",     5002,	"Port number of Mac sniffing, only available when MAC SNIFF enabled.", "", 0, "port"),
        ("PORT",    "FACE",	        5010,	"Port number of Face detection, only available when Face detection enabled.", "", 0, "port"),
        ("PORT",    "SNAPSHOT",	    5020,	"Port number of Snapshot recording, only available when SNAPSHOT enabled.", "", 0, "port"),
        ("PORT",    "QUERY_DB",	    5080,	"Enable Access database from remote machine, not available at the time", "", 0, "port"),
        ("DB_COMMON", "USER",       "users", "Mysql Common DB table for users", "", 1, "sz"),
        ("DB_COMMON", "ACCOUNT",    "users",	"Mysql Common DB table for users", "", 1, "sz"),
        ("DB_COMMON", "PARAM",      "params", "Mysql Common DB table for parameters of devices", "", 1, "sz"),
        ("DB_COMMON", "SNAPSHOT",   "snapshot", "Mysql Common DB table for snapshot", "", 1, "sz"),
        ("DB_COMMON", "COUNTING",   "counting_report_10min", "Mysql Common DB table for counting", "", 1, "sz"),
        ("DB_COMMON", "COUNT_EVENT","counting_event", "Mysql Common DB table for counting when counting mode is event type", "", 1, "sz"),
        ("DB_COMMON", "FACE",       "face_thumbnail", "Mysql Common DB table for face detection", "", 1, "sz"),
        ("DB_COMMON", "HEATMAP",    "heatmap", "Mysql Common DB table for heatmap", "", 1, "sz"),
        ("DB_COMMON", "MAC",        "MacSniff", "Mysql Common DB table for mac sniffing", "", 1, "sz"),
        ("DB_COMMON", "ACCESS_LOG", "access_log", "Mysql Common DB table for access web service log", "", 1, "sz"),
        ("DB_COMMON", "MESSAGE",    "message", "Mysql Common DB table for message", "", 1, "sz"),
        ("DB_CUSTOM", "ACCOUNT",    "users", "Mysql Custom DB table for users", "", 1, "sz"),
        ("DB_CUSTOM", "COUNT",      "count_tenmin", "Mysql Custom DB table for counting service", "", 1, "sz"),
        ("DB_CUSTOM", "HEATMAP",    "heatmap", "Mysql Custom DB table for heatmap service", "", 1, "sz"),
        ("DB_CUSTOM", "AGE_GENDER", "age_gender", "Mysql Custom DB table for age and gender", "", 1, "sz"),
        ("DB_CUSTOM", "MACSNIFF",   "macsniff", "Mysql Custom DB table for mac sniffing", "", 1, "sz"),
        ("DB_CUSTOM", "SQUARE",     "square", "Mysql Custom DB table for square", "", 1, "sz"),
        ("DB_CUSTOM", "STORE",      "store", "Mysql Custom DB table for store", "", 1, "sz"),
        ("DB_CUSTOM", "CAMERA",     "camera", "Mysql Custom DB table for camera(device)", "", 1, "sz"),
        ("DB_CUSTOM", "COUNTER_LABEL","counter_label", "Mysql Custom DB table for counter label of device", "", 1, "sz"),
        ("DB_CUSTOM", "LANGUAGE",   "language", "Mysql Custom DB table for language", "", 1, "sz"),
        ("FPP",       "HOST",       "api-cn.faceplusplus.com", "Face++ api host", "", 0, "ipv4"),
        ("FPP",       "API_KEY",    "", "Face++ api key for face detection, please visit www.faceplusplus.com.cn", "", 0, "sz"),
        ("FPP",       "API_SRCT",   "", "Face++ api secret for face detection, please visit www.faceplusplus.com.cn", "", 0, "sz"),
        ("WEATHER",   "HOST",       "tianqiapi.com", "Weather service api host, not available at the time", "", 0, "sz"),
        ("WEATHER",   "API_KEY",    "", "Weather service api key, not available at the time", "", 0, "sz"),
        ("WEATHER",   "API_SRCT",   "", "Weather service api secret, not available at the time", "", 0, "sz"),
        ("MISC",      "AGE_GROUP",  "[0,18,30,45,65]", "Age group of age database, default [0,18,30,45,65] means [0~17, 18~29, 30~44, 45~64, 65~99]", "", 0, "sz"),
        ("VERSION",   "WEB",        "0.7.0", "version of web page's", "", 1, "sz"),
        ("VERSION",   "BIN",        "0.9.2", "version of binary", "", 1, "sz"),
        ("LICENSE",   "CODE",       "", "License for this machine.", "", 0, "sz")]


    for r in config_rows:
        desc = r[3].replace("'", "&#039;")
        sq  = "INSERT INTO " + table + "( SECTION, entryName, entryValue, description, option, readonly, datatype) "
        sq += "VALUES('%s', '%s', '%s', '%s', '%s', %d, '%s' )" %(r[0], r[1], r[2], desc, r[4], r[5], r[6])
        print (sq)
        cur.execute(sq)
    
    dbsqcon.commit()
    print (".", end='')

    cur.close()
    dbsqcon.close()

    print ("  complete ")
    if os.name == 'posix':
        os.chdir(rootdir)
        os.system("chown www-data " + fname )
        os.system("chown -R www-data " + fname )

        os.chdir("../")
        os.system("chown  www-data bin")
        os.system("chown -R www-data bin")

        os.chdir("bin")


def make_param_db(fname="param.db", table = "param_tbl"):
    print ("Initializing PARAM DB .", end='')
    dbsqcon = sqlite3.connect(fname)
    cur = dbsqcon.cursor()
    sq = "DROP TABLE IF EXISTS " + table
    cur.execute(sq)

    sq = """CREATE TABLE IF NOT EXISTS """ + table + """ (
        prino INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
        groupPath TEXT,
        entryName TEXT,
        entryValue TEXT,
        description TEXT,
        datatype TEXT default 'sz',
        option TEXT,
        create_permission INTEGER default 7,
        delete_permission INTEGER default 7,
        update_permission INTEGER default 7,
        read_permission INTEGER default 7,
        readonly INTEGER default 0,
        writeonly INTEGER default 0,
        group1 TEXT,
        group2 TEXT,
        group3 TEXT,
        group4 TEXT,
        group5 TEXT,
        group6 TEXT,
        made TEXT,
        regdate NUMERIC
    )"""
    cur.execute(sq)
    dbsqcon.commit()
    
    ###### groupPath, entryValue, datatype, option, description, readonly, writeonly
    param_rows = [
        ("software.root.webpage.document_title", "BUSINESS INTELLIGENCE", "sz", "", "Title of Platform", 0, 0),
        ("software.root.webpage.host_title", "Business Intelligence", "sz", "", "Title of web page", 0, 0),
        ("software.root.webpage.logo_path", "/libs/pfh_logo.png", "sz", "", "path of log file, png, jpg etc", 0, 0),
        ("software.root.webpage.developer", "hanskim", "sz", "", "Name of developer", 1, 0),
        ("software.service.application", "PEOPLE", "select", "people,traffic", "Application of this platform, Traffic is not available at the time.", 1, 0),
        ("software.service.counting.mode", "ACTIVE", "select", "active,tlss", "Mode of Counting Service, TLSS: Hosted service, ACTIVE: Platform polling each device in Local area network, EVENT: each device push data to platform", 0, 0),
        ("software.service.count_event", "no", "select", "no,http,tcp","realtime push counting servery", 0, 0),
        ("software.service.counting", "yes", "yesno","yes,no", "enable Counting service", 0, 0),
        ("software.service.face", "no", "yesno", "yes,no", "Enable face detection,age and gender, need face detection camera and face++ account", 0, 0),
        ("software.service.macsniff", "no", "yesno", "yes,no", "Enable Mac sniff", 0, 0),
        ("software.service.snapshot", "no", "yesno", "yes,no",  "Enable Snapshot recording, every 10 minutee", 0, 0),
        ("software.service.probe_interval", 30,  "int", "", "time(secs) of proble", "30|600", 0, 0),
        ("software.service.root_dir", "/var/www/", "sz", "", "Root dictory of platform", 0, 0),
        ("software.service.start_on_boot", "yes", "yesno", "yes,no", "Auto start on boot on windows, already enabled on linux system", 0, 0),
        ("software.mysql.host", "localhost", "sz", "", "Maria DB host, default localhost if platform is installed on the machine", 0, 0),
        ("software.mysql.user", "ct_user", "sz", "", "Mysql sql user, not root or admin ID", 0, 0),
        ("software.mysql.password", "13579", "sz", "", "Mysql password for user ID", 0, 0),
        ("software.mysql.db", "common", "sz", "", "Mysql Common db name", 0, 0),
        ("software.mysql.charset", "utf8", "sz", "", "Charset of Mysql, default utf-8", 0, 0),
        ("software.mysql.recycling_time", 259200, "int", "", "Recycling time(days) of Common database, erased if recycling time pass over and already data transfer to custom database.", 0, 0),
        ("software.service.tlss.port", 5000, "port", "", "Port number of Hosted service, only available when TLSS service enabled.", 0, 0),
        ("software.service.count_event.port", 5030, "port", "", "Port number of Count event, only available when COUNT EVENT enabled.", 0, 0),
        ("software.service.macsniff.port", 5002, "port", "", "Port number of Mac sniffing, only available when MAC SNIFF enabled.", 0, 0),
        ("software.service.face.port", 5010, "port", "", "Port number of Face detection, only available when Face detection enabled.", 0, 0),
        ("software.service.snapshot.port", 5020, "port", "", "Port number of Snapshot recording, only available when SNAPSHOT enabled.", 0, 0),
        ("software.service.query_db.port", 5080, "port", "", "Enable Access database from remote machine, not available at the time", 0, 0),
        ("software.mysql.db_common.table.user", "users", "sz", "", "Mysql Common DB table for users", 0, 0),
        ("software.mysql.db_common.table.account", "users", "sz", "", "Mysql Common DB table for users", 0, 0),
        ("software.mysql.db_common.table.param", "params", "sz", "", "Mysql Common DB table for parameters of devices", 0, 0),
        ("software.mysql.db_common.table.snapshot", "snapshot", "sz", "", "Mysql Common DB table for snapshot", 0, 0),
        ("software.mysql.db_common.table.counting", "counting_report_10min", "sz", "", "Mysql Common DB table for counting", 0, 0),
        ("software.mysql.db_common.table.count_event", "counting_event", "sz", "", "Mysql Common DB table for counting when counting mode is event type", 0, 0),
        ("software.mysql.db_common.table.face", "face_thumbnail", "sz", "", "Mysql Common DB table for face detection", 0, 0),
        ("software.mysql.db_common.table.heatmap", "heatmap", "sz", "", "Mysql Common DB table for heatmap", 0, 0),
        ("software.mysql.db_common.table.macsniff", "MacSniff", "sz", "", "Mysql Common DB table for mac sniffing", 0, 0),
        ("software.mysql.db_common.table.access_log", "access_log", "sz", "", "Mysql Common DB table for access web service log", 0, 0),
        ("software.mysql.db_common.table.message", "message", "sz", "", "Mysql Common DB table for message", 0, 0),
        ("software.mysql.db_custom.table.user", "users", "sz", "", "Mysql Custom DB table for users", 0, 0),
        ("software.mysql.db_custom.table.account", "users", "sz", "", "Mysql Custom DB table for users", 0, 0),
        ("software.mysql.db_custom.table.count", "count_tenmin", "sz", "", "Mysql Custom DB table for counting service", 0, 0),
        ("software.mysql.db_custom.table.heatmap", "heatmap", "sz", "", "Mysql Custom DB table for heatmap service", 0, 0),
        ("software.mysql.db_custom.table.age_gender", "age_gender", "sz", "", "Mysql Custom DB table for age and gender", 0, 0),
        ("software.mysql.db_custom.table.macsniff", "macsniff", "sz", "", "Mysql Custom DB table for mac sniffing", 0, 0),
        ("software.mysql.db_custom.table.square", "square", "sz", "", "Mysql Custom DB table for square", 0, 0),
        ("software.mysql.db_custom.table.store", "store", "sz", "", "Mysql Custom DB table for store", 0, 0),
        ("software.mysql.db_custom.table.camera", "camera", "sz", "", "Mysql Custom DB table for camera(device)", 0, 0),
        ("software.mysql.db_custom.table.counter_label", "counter_label", "sz", "", "Mysql Custom DB table for counter label of device", 0, 0),
        ("software.mysql.db_custom.table.language", "language", "sz", "", "Mysql Custom DB table for language", 0, 0),
        ("software.fpp.host", "api-cn.faceplusplus.com", "sz", "", "Face++ api host", 0, 0),
        ("software.fpp.port", 443, "port", "", "Face++ api port", 0, 0),
        ("software.fpp.api_key", "", "sz", "", "Face++ api key for face detection, please visit www.faceplusplus.com.cn", 0, 0),
        ("software.fpp.api_srct", "", "sz", "","Face++ api secret for face detection, please visit www.faceplusplus.com.cn", 0, 0),
        ("software.weather.host", "tianqiapi.com", "sz", "",  "Weather service api host, not available at the time", 0, 0),
        ("software.weather.port", 443, "port", "",  "Weather service api port, not available at the time", 0, 0),
        ("software.weather.api_key", "", "sz", "", "Weather service api key, not available at the time", 0, 0),
        ("software.weather.api_srct", "", "sz", "", "Weather service api secret, not available at the time", 0, 0),
        ("software.webpage.age_group", "[0,18,30,45,65]", "sz","", "Age group of age database, default [0,18,30,45,65] means [0~17, 18~29, 30~44, 45~64, 65~99]", 0, 0),
        ("software.webpage.version", "0.7.0", "sz", "", "version of web page's", 0, 0),
        ("software.bin.version", "0.9.2", "sz", "", "version of binary", 0, 0),
        ("software.service.license.code", "", "sz", "", "License for this machine.", 0, 0),
        ("software.service.license.exp_date", "", "sz", "", "License Expire date for this machine.", 0, 0),
        ("software.service.license.timestamp", "", "sz", "", "License Expire timestamp xfor this machine.", 0, 0),
        
        ("system.network.eth0.hwaddr", "", "sz", "", "Mac address of eth0", 0, 0),
        ("system.network.eth0.connected", "", "yesno", "yes,no", "Status of eth0 physical connection", 0, 0),

        ("system.network.eth0.ip4.mode", "", "select", "dhcp,static", "Mode of Ethernet", 0, 0),
        ("system.network.eth0.ip4.changed", "no", "yesno", "yes,no", "changed network", 0, 0),
        ("system.network.eth0.ip4.address", "", "ipv4", "", "Ethernet Address", 0, 0),
        ("system.network.eth0.ip4.subnetmask", "", "ipv4", "", "Ethernet subnetmask", 0, 0),
        ("system.network.eth0.ip4.gateway", "", "ipv4", "", "Ethernet gateway", 0, 0),
        ("system.network.eth0.ip4.dns1", "", "ipv4", "", "Ethernet dns1", 0, 0),
        ("system.network.eth0.ip4.dns2", "", "ipv4", "", "Ethernet dns2", 0, 0),

        ("system.network.eth0.ip6.enable", "no", "yesno", "yes,no", "IP6 enabled", 0, 0),
        ("system.network.eth0.ip6.mode", "dhcp", "select", "dhcp,static", "Mode of Ethernet", 0, 0),
        ("system.network.eth0.ip6.changed", "no", "yesno", "yes,no", "changed network setting", 0, 0),
        ("system.network.eth0.ip6.address", "", "ipv6", "", "Ethernet Address", 0, 0),
        ("system.network.eth0.ip6.subnetmask", "", "ipv4", "", "Ethernet subnetmask", 0, 0),
        ("system.network.eth0.ip6.gateway", "", "ipv6", "", "Ethernet gateway", 0, 0),
        ("system.network.eth0.ip6.dns1", "", "ipv6", "", "Ethernet dns1", 0, 0),
        ("system.network.eth0.ip6.dns2", "", "ipv6", "", "Ethernet dns2", 0, 0),

        ("system.network.wlan0.hwaddr", "", "sz", "", "Mac address of wlan0", 0, 0),
        ("system.network.wlan0.connected", "", "yesno", "yes,no", "Status of wlan0 physical connection", 0, 0),
        ("system.network.wlan0.ip4.enable", "no", "yesno", "yes,no", "wifi enabled", 0, 0),
        ("system.network.wlan0.ip4.mode", "", "select", "dhcp,static", "Mode of Ethernet", 0, 0),
        ("system.network.wlan0.ip4.changed", "no", "yesno", "yes,no", "changed network setting", 0, 0),
        ("system.network.wlan0.ip4.address", "", "ipv4", "", "Ethernet Address", 0, 0),
        ("system.network.wlan0.ip4.subnetmask", "", "ipv4", "", "Ethernet subnetmask", 0, 0),
        ("system.network.wlan0.ip4.gateway", "", "ipv4", "", "Ethernet gateway", 0, 0),
        ("system.network.wlan0.ip4.dns1", "", "ipv4", "", "Ethernet dns1", 0, 0),
        ("system.network.wlan0.ip4.dns2", "", "ipv4", "", "Ethernet dns2", 0, 0),
   
    ]


    for r in param_rows:
        exp = r[0].split(".")
        grps = ["","","", "", "", ""]
        groupPath=""
        for i, e in enumerate(exp):
            grps[i] = e
            if i < len(exp)-1:
                if groupPath:
                    groupPath +="."
                groupPath += e

        entryName = exp.pop()

        sq  = "INSERT INTO " + table + "( groupPath, entryName, entryValue, datatype, option, description, group1, group2, group3, group4, group5, group6, readonly, writeonly, made,  regdate, create_permission, delete_permission, update_permission, read_permission) "
        sq += "VALUES('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', %s, %s, '%s', %s, 0,0,7,7 )" %(groupPath, entryName, r[1], r[2], r[3], r[4].replace("'", "&#039;"), grps[0], grps[1], grps[2], grps[3], grps[4], grps[5], r[5], r[6], 'hanskim', int(time.time()))

        print (sq)
        cur.execute(sq)
    
    dbsqcon.commit()
    print (".", end='')

    cur.close()
    dbsqcon.close()

    print ("  complete ")


def make_info_db(fname="param.db", table = "info_tbl"):
    print ("Initializing INFO DB .", end='')
    dbsqcon = sqlite3.connect(fname)
    cur = dbsqcon.cursor()
    sq = "DROP TABLE IF EXISTS " + table
    cur.execute(sq)

    sq = """CREATE TABLE IF NOT EXISTS """ + table + """(
        prino INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
        category TEXT,
        entryName TEXT,
        entryValue TEXT,
        description TEXT,
        regdate NUMERIC
    )"""
    cur.execute(sq)
    dbsqcon.commit()
    print (".", end='')

    cur.close()
    dbsqcon.close()

if __name__ == '__main__':
    # print (rootdir)
    # make_param_db()

    # sys.exit()

    # mysqld_op = is_alive_mysqld()

    # if not mysqld_op:
    #     print ("mysqld is not running")
    #     r = start_mysqld()
    #     if not r:
    #         sys.exit()
    #     for i in range (0, 10):
    #         mysqld_op = is_alive_mysqld()
    #         print(mysqld_op)
    #         if mysqld_op:
    #             print ("mysql is running!")
    #             break
    #         if i>8:
    #             print ("mysql fail, check folder name includes chinese, space, special charactors")
    #             sys.exit()
    #         time.sleep(2)
    
    # db_location = os.path.dirname(mysqld_op)
    db_location=""
    if os.name == 'nt':	
        db_location = os.path.dirname(db_location)
        window = Tk()
        window.title("Initialize Database")
        window.geometry("460x400")
        window.resizable(True, True)

        lb_db_location = Label(window, text = "DB Location")
        lb_db_location.grid(row=0, column=0, sticky="w", ipadx=5, ipady=10)
        Label(window, text = db_location).grid(row=0, column=1, columnspan=2, sticky="w", ipadx=10, ipady=10)
        lb_db_file = Label(window, text = "DB File")
        lb_db_file.grid(row=1, column=0, sticky="w", ipadx=5, ipady=5)
        entDiag = Entry(window, width=40)
        entDiag.grid(row=1, column=1, sticky="w", ipadx=10)
        entDiag.insert(0, default_db_file)	
        btn_browse = Button(window, text="Browse", command=browse_dir, width=10, height=1)
        btn_browse.grid(row=1, column=2, sticky="e", padx=5,  ipadx=10)

        tx = Text(window, height=15, width=58)
        tx.grid(row=2, column=0, columnspan=3, sticky="n", padx=10, pady=5, ipadx=10, ipady=10)

        btn_cancel = Button(window, text="Cancel", command=window.destroy, width=10, height=1)
        btn_cancel.grid(row=3, column=1, sticky="e", pady=15, padx=5, ipadx=10)
        btn_init = Button(window, text="Init DB", command=init_DB, width=10, height=1)
        btn_init.grid(row=3, column=2, sticky="e", pady=15, padx=5, ipadx=10)

        prog = ttk.Progressbar(window, maximum=500, length=430, mode="determinate")
        prog.grid(row=4, column=0, columnspan=3, pady=6)
        prog["value"] = 0
        

        if LOCALE[0] == 'zh_CN':
        # if LOCALE[0] == 'ko_KR':
            window.title("数据库初始化")
            lb_db_location.configure(text = "数据库位置")
            lb_db_file.configure(text = "数据库文件")
            btn_browse.configure(text="浏览")
            btn_cancel.configure(text="取消")
            btn_init.configure(text="开始初始化")
            message("软件 初始化数据库。\n数据库里面所以的资料会删除。\n初始化前请备份资料。")
        else :
            message("This program is for initializing database, \nall data will be lost\nPlease backup database before initializing")

        window.mainloop()
    

    elif os.name =='posix':
        prog ={'value':0, 'maximum': 100}
        total = 200
        def progress():
            global prog
            p = floor(prog["value"] * 100 / prog["maximum"] )
            line = ""
            for i in range(p) :
                line += "="
            line += "%d%s" %(p, '%')

            for i in range(p, 100) :
                line += " "

            line = "[" + line + "]"
            print ("\r" + line, end='' )


        
        print ("\n\n\n")
        db_file = input("Enter db file(Using default, press ENTER) :" )
        if not db_file.strip():
            db_file = default_db_file
        
        if not os.path.isfile(db_file):
            print (" == The file %s  is not in the location" %db_file)
            exit()
        adminid = input("Admin ID(default:admin):")
        if not adminid.strip():
            adminid = "admin"
        adminpass = input("Admin password:")

        db_userid = input("DB user ID(default:ct_user):")
        if not db_userid.strip():
            db_userid = "ct_user"
        db_userpass = input("db_user password(defalut:13579):")
        if not db_userpass.strip():
            db_userpass = "13579"

        print ("\n")
        print ("======================================================================================")
        print ("  This program is for initializing database, \n  all data will be lost\n  Please backup database before initializing\n\n")
        print ("  DB File         : %s" %db_file)
        print ("  Admin ID        : %s" %adminid)
        print ("  Admin password  : %s" %adminpass)
        print ("  User ID         : %s" %db_userid)
        print ("  User  password  : %s" %db_userpass)        
        print ()

        okcancel = input("Are you sure to proceed? (y/N)" )

        if okcancel.upper().strip() == 'Y':
            init_db(db_file, rootid = adminid, rootpass=adminpass)
            create_db_user(db_userid, db_userpass, adminid, adminpass)
            make_log_db()
            make_config_db()
        else :
            print ("Canceled")
            sys.exit()
        # print ("======================================================================================")
        


    
