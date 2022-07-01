change_log = """
2021-05-06, initial programming.


"""
# wget http://49.235.119.5/download.php?file=../bin/update.py -O /var/www/bin/update.py

import time, sys, os
import socket
import requests
from http.client import HTTPConnection
import json, re
import pymysql, sqlite3
import py_compile
import logging
import locale
import optparse
import uuid

op = optparse.OptionParser()
op.add_option("-V", "--version", action="store_true", dest="_VERSION")
op.add_option("-W", "--windows-gui", action="store_true", dest="_WIN_GUI")
op.add_option("-S", "--server-address", action = "store", type="string", dest="_SERVER_IP")
opt, args = op.parse_args()

if os.name == 'nt':
    import winreg

_SERVER_IP = opt._SERVER_IP if opt._SERVER_IP else '49.235.119.5'
_WIN_GUI = True if opt._WIN_GUI and os.name == 'nt' else False
_SERVER_MAC = "525400C9FE37"

# _WIN_GUI = True

if (_WIN_GUI == True):
    from tkinter import *
    from tkinter import ttk
    import zipfile



MYSQL = {'HOST':'localhost', 'USER':'root', 'PASS':'rootpass'}
MYSQL_VERSION = "10.4.12"

version = {
    "bin": 0.95,
    "webpage":0.74,
    "param": 0.95,
    "update": 0.92,
    "code": int(time.time()),
}

_ROOT_DIR = os.path.dirname(os.path.dirname(os.path.abspath(sys.argv[0])))
config_db_file = _ROOT_DIR + "/bin/param.db"
_mysql_port = 0

log = logging.getLogger("startBIupdate")
logging.basicConfig(
    filename = _ROOT_DIR + "/bin/log/update.log",
    format = "%(levelname)-8s  %(asctime)s %(module)s %(funcName)s %(lineno)s %(message)s %(threadName)s",
    level=logging.INFO
)

# DOWNPAGE=http://49.235.119.5/download.php?file=
# BASEDIR=/var/www/
arrHtmlFiles = (
    "html/404.html",
    "html/admin.php", 
    "html/agereport.php",
    "html/countreport.php",
    "html/genderreport.php",
    "html/index.php",
    "html/main.php",
    "html/pubSVC.php",
    "html/inc/auth.php",
    "html/inc/common.php",
    "html/inc/config.php",
    "html/inc/extra.php",
    "html/inc/log.php",
    "html/inc/system.php",
    "html/inc/pageSide.php",
    "html/inc/query.php",
    "html/inc/query_functions.php",
    "html/inc/page_functions.php",
    "html/inc/param.php",
    "html/inc/profile.php",
    "html/inc/database.php",
    "html/inc/device_tree.php",
    "html/inc/webpage_config.php",
    "html/js/app.js",
    "html/js/jquery.min.js",
    "html/js/admin.js",
    "html/js/custom.js",
    "html/js/genderGraph.js",
    "html/js/heatmap.js",
    "html/js/chart.js",
    "html/js/jstree.min.js",
    "html/js/main.js",
    "html/libs/dbconnect.php",
    "html/libs/functions.php",
    "html/libs/pfh_logo.png",
    "html/css/all.css",
    "html/css/app.css",
    "html/css/classic.css",
    "html/css/corporate.css",
    "html/css/modern.css",
    "html/css/tree-view.css",
    "html/css/webfonts/fa-brands-400.eot",
    "html/css/webfonts/fa-brands-400.svg",
    "html/css/webfonts/fa-brands-400.ttf",
    "html/css/webfonts/fa-brands-400.woff",
    "html/css/webfonts/fa-brands-400.woff2",
    "html/css/webfonts/fa-regular-400.eot",
    "html/css/webfonts/fa-regular-400.svg",
    "html/css/webfonts/fa-regular-400.ttf",
    "html/css/webfonts/fa-regular-400.woff",
    "html/css/webfonts/fa-regular-400.woff2",
    "html/css/webfonts/fa-solid-900.eot",
    "html/css/webfonts/fa-solid-900.svg",
    "html/css/webfonts/fa-solid-900.ttf",
    "html/css/webfonts/fa-solid-900.woff",
    "html/css/webfonts/fa-solid-900.woff2",

)

arrBinFiles = (
    "bin/chkLic_s.py",
    "bin/cgis.py",
    "bin/functions_s.py",
    "bin/parse_functions.py",
    "bin/counting_main.py",
    "bin/active_counting.py",
    "bin/event_counting.py",
    "bin/proc_event.py",
    "bin/tlss_counting.py",
    "bin/db_functions.py",
    "bin/face_det.py",
    "bin/function4php.py",
    "bin/init_db.py",
    "bin/proc_db.py",
    "bin/repair_db.py",
    "bin/startBI.py",
    "bin/param_tbl.ini",
    "bin/sysdaemon.py",
    "bin/monitor.py",
    "bin/monitor.json",
    "bin/update.py",
)

arrDelFiles= (
    "monitor.py",
    "bin/function4php.exe",
    "bin/init_db.exe",
    "bin/monitor.exe",
    "bin/startBI.exe",
    "bin/update.exe",
    "bin/init_db.py",
    "bin/install.py",
    "bin/apply_netconfig.sh",
    "html/log.php",
    "html/config.php",
    "html/libs/logo_sidway.png",
    "html/libs/logo_pingfanghe.png",
    "html/libs/logo_pingfanghe_inv.png",
)

########################################################################################################################################################
#####################################################   File download   ################################################################################
def is_online(ip, port=80):
	#  if port is not 80 ??
	s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
	server = (ip, port)
	s.settimeout(1)
	try:
		s.connect(server)
	except Exception as e:
		# print(e)
		s.close()
		return False
	
	s.close()
	return True	

def getMyPublicIP():
    try:
        _my_ip = requests.get("http://api.ipify.org").text
        return _my_ip
    except:
        _my_ip= requests.get("http://ip.42.pl/raw").text

    return _my_ip

def patchHtml():
    global _SERVER_IP
    global arrHtmlFiles
    global _ROOT_DIR

    prints ("===Updating webpage html files===")
    if checkAvailabe() == False:
        prints("Server unavailable")
        return False

    targetdir = _ROOT_DIR
    if  os.name == 'nt':
        targetdir = "%s/nginx" %_ROOT_DIR

    server = (_SERVER_IP, 80)
    conn = HTTPConnection(*server)
    progress(0, 0, "patchHTML")

    for i, file in enumerate(arrHtmlFiles):
        fname = "%s/%s" %(targetdir, file)
        if os.path.isfile(fname) and (file.startswith("html/css/") or file.startswith("html/js/app.js")):
            progress((i+1), len(arrHtmlFiles),'patchHTML')
            continue
        conn.putrequest("GET", "/download.php?file=%s" %file) 
        conn.endheaders()
        rs = conn.getresponse()
        prints(fname)
        if not (os.path.isdir(os.path.dirname(fname))) :
            os.mkdir(os.path.dirname(fname))

        with open(fname, "wb")  as f:
            progress((i+1), len(arrHtmlFiles), "patchHTML")
            f.write(rs.read())
    
    progress(100, 100, "patchHTML")
    conn.close()
    print ()

def patchBin():
    global _SERVER_IP
    global arrBinFiles
    global _ROOT_DIR

    prints ("Updating Binary python files")
    if checkAvailabe() == False:
        prints("Server unavailable")
        return False

    server = (_SERVER_IP, 80)
    conn = HTTPConnection(*server)
    progress(0, 0, "patchBinary")
    for i, file in enumerate(arrBinFiles):
        conn.putrequest("GET", "/download.php?file=%s" %file) 
        conn.endheaders()
        rs = conn.getresponse()
        fname = "%s/%s" %(_ROOT_DIR, file)
        prints(fname)
        if not (os.path.isdir(os.path.dirname(fname))) :
            os.mkdir(os.path.dirname(fname))

        with open(fname, "wb")  as f:
            progress((i+1), len(arrBinFiles), "patchBinary")
            f.write(rs.read())
    progress(100, 100, "patchBinary")
    conn.close()
    print ()
    py_compile.compile("%s/bin/chkLic_s.py" %_ROOT_DIR, "%s/bin/chkLic.pyc" %_ROOT_DIR)
    py_compile.compile("%s/bin/functions_s.py" %_ROOT_DIR, "%s/bin/functions.pyc" %_ROOT_DIR)
    py_compile.compile("%s/bin/function4php.py" %_ROOT_DIR, "%s/bin/function4php.pyc" %_ROOT_DIR)
    os.unlink("%s/bin/chkLic_s.py" %_ROOT_DIR)
    # os.unlink("%s/bin/functions_s.py" %_ROOT_DIR)


def delUnnessaries():
    global arrDelFiles
    global _ROOT_DIR

    if checkAvailabe() == False:
        return False    
    print ("Delete Unnessaries...")

    arrDelFiles = list(arrDelFiles)
    # for (dirpath, dirname, filename) in os.walk(_ROOT_DIR+"\\Mariadb\\data"):
    #     arrDelFiles.extend(filename)
    if os.name == 'nt':
        arr =  os.listdir(_ROOT_DIR+"\\Mariadb\\data")
        for fname in arr:
            if (os.path.isdir(_ROOT_DIR + "\\Mariadb\\data\\" + fname)):
                continue
            if fname.lower() == "my.ini":
                continue
            if fname.lower().startswith("ibdata"):
                continue

            arrDelFiles.append("Mariadb\\data\\" + fname)

    for i, fname in enumerate(arrDelFiles):
        if os.name == 'nt' and fname.startswith("html/"):
            fname = "%s/Nginx/%s" %(_ROOT_DIR, fname)
        else :
            fname = "%s/%s" %(_ROOT_DIR, fname)
        if os.path.isfile(fname):
            try:
                os.unlink(fname)
                print (fname, " deleted")
            except:
                pass
        progress((i+1), len(arrDelFiles))

    print()


def makeLink(): # windows only
    if os.name != 'nt':
        print ("This function is only for windows operationg system")
        return False

    rootdrive = _ROOT_DIR.split("\\")[0]

    if not os.path.exists( _ROOT_DIR + "\\DB_BACKUP\\"):
        os.mkdir("%s\\DB_BACKUP\\" %_ROOT_DIR)

    fname = _ROOT_DIR + "\\monitor.bat"
    with open(fname, "w") as f:
        f.write(rootdrive + "\n")
        f.write("cd \"" + _ROOT_DIR + "\\bin\"\n")
        f.write("python3.exe monitor.py\n")

    fname = _ROOT_DIR + "\\update.bat"
    with open(fname, "w") as f:
        f.write("@echo off\n")
        f.write("cd bin\n")
        f.write("python3.exe update.py\n")
        f.write("pause\n")

    fname = _ROOT_DIR + "\\bin\\start.bat"
    with open(fname, "w") as f:
        f.write(rootdrive + "\n")
        f.write("cd \"" + _ROOT_DIR + "\\bin\"\n")
        f.write("python3.exe update.py\n")
        f.write("python3.exe startBI.py\n")

    fname = _ROOT_DIR + "\\backupDB.bat" 
    with open(fname, "w") as f:
        f.write(rootdrive + "\n")
        f.write("cd \"" + _ROOT_DIR + "\\bin\"\n")
        f.write("python3.exe sysdaemon.py backup\n")

    fname = _ROOT_DIR + "\\repairDB.bat" 
    with open(fname, "w") as f:
        f.write(rootdrive + "\n")
        f.write("cd \"" + _ROOT_DIR + "\\bin\"\n")
        f.write("python3.exe repair_db.py\n")
        f.write("pause\n")


########################################################################################################################################################
##############################################  PARAM TABLE ############################################################################################
########################################################################################################################################################

def sqlDbMaster():
	global config_db_file
	if not os.path.isfile(config_db_file):
		prints ("No config db file")
		return False

	conn = sqlite3.connect(config_db_file)
	conn.execute("PRAGMA journal_mode=WAL")
	return conn

def configVars(groupPath=''):
	arr_rs = dict()
	arr= []
	sq = ""
	if groupPath.strip():
		for i, x in enumerate(groupPath.split(".")):
			arr.append("group%d = '%s'" %((i+1),x))
		
		sq = " and ".join(arr)
	if sq:
		sq = " where " + sq + " "

	sq = "select entryValue, entryName, groupPath from param_tbl " + sq 
	# print(sq)
	configdbconn = sqlDbMaster()
	with configdbconn:
		cur = configdbconn.cursor()
		cur.execute(sq)
		rows = cur.fetchall()
		if not rows:
			return ''
		if len(rows) == 1 :
			return rows[0][0]
		
		for r in rows:
			arr_rs[r[2]+"."+r[1]] = r[0]
	return arr_rs



def patchParamDb():
    global _ROOT_DIR
    fname_ini = "%s/bin/param_tbl.ini" %_ROOT_DIR
    fname_db  = "%s/bin/param.db" %_ROOT_DIR
    # print(fname_ini)
    prints ("patching  Param DB from %s" %fname_ini)
    arr_list = list()
    arr_sq = list()
    arr_grps = list()
    
    if not os.path.isfile(fname_ini):
        prints ("Error, File %s is not exist" %fname_ini)
        return False

    with open (fname_ini, "r", encoding='utf-8') as f:
        body = f.read()

    for line in body.splitlines():
        line = line.strip()
        if not line or line[0] == "#":
            continue
        line = line.replace("'", "&#039;")
        arr = json.loads('['+line+']')
        arr_list.append(tuple(arr))
        arr_grps.append(arr[0])

    dbsqcon = sqlite3.connect(fname_db)
    cur = dbsqcon.cursor()
    sq = """CREATE TABLE IF NOT EXISTS param_tbl (\
        prino INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,\
        groupPath TEXT,\
        entryName TEXT,\
        entryValue TEXT,\
        description TEXT,\
        datatype TEXT default 'sz',\
        option TEXT,\
        create_permission INTEGER default 7,\
        delete_permission INTEGER default 7,\
        update_permission INTEGER default 7,\
        read_permission INTEGER default 7,\
        readonly INTEGER default 0,\
        writeonly INTEGER default 0,\
        group1 TEXT,\
        group2 TEXT,\
        group3 TEXT,\
        group4 TEXT,\
        group5 TEXT,\
        group6 TEXT,\
        made TEXT,\
        regdate NUMERIC\
    )"""
    arr_sq.append(sq)
    arr_sq.append('commit')

    sq = """CREATE TABLE IF NOT EXISTS info_tbl(\
        prino INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,\
        category TEXT,\
        entryName TEXT,\
        entryValue TEXT,\
        description TEXT,\
        regdate NUMERIC\
    )"""

    arr_sq.append(sq)
    arr_sq.append('commit')

    for r in arr_list:
        # sq = "select * from sqlite_master where name='param_tbl'"
        exp = r[0].split(".")
        grps = ["", "", "", "", "", ""]
        groupPath=""
        for i, e in enumerate(exp):
            grps[i] = e
            if i < len(exp)-1:
                if groupPath:
                    groupPath +="."
                groupPath += e

        entryName = exp.pop()
        sq = "select prino from param_tbl where groupPath='%s' and entryName='%s'" %(groupPath, entryName)
        # print (sq)
        cur.execute(sq)
        row = cur.fetchone()
        if (row == None):
            sq  = "INSERT INTO param_tbl( groupPath, entryName, entryValue, datatype, option, description, group1, group2, group3, group4, group5, group6, readonly, writeonly, made,  regdate, create_permission, delete_permission, update_permission, read_permission) "
            sq += "VALUES('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', %s, %s, '%s', %s, 0,0,7,7 )" %(groupPath, entryName, r[1], r[2], r[3], r[6], grps[0], grps[1], grps[2], grps[3], grps[4], grps[5], r[4], r[5], 'hanskim', int(time.time()))
        else:
            sq = "UPDATE param_tbl set datatype='%s', option='%s', description='%s', readonly='%s', writeonly='%s' where prino=%s" %(r[2], r[3], r[6], r[4], r[5], row[0])
        arr_sq.append(sq)

    arr_sq.append('commit')

    # MAC
    mac = "%012X" %(uuid.getnode())
    arr_sq.append("update param_tbl set entryValue='%s' where groupPath='system.network.eth0' and entryName='hwaddr'" %mac)
    
    # version
    arr_sq.append("update param_tbl set entryValue='%s' where groupPath='software.bin' and entryName='version'" %(str(version["bin"])))
    arr_sq.append("update param_tbl set entryValue='%s' where groupPath='software.webpage' and entryName='version'" %(str(version["webpage"])))
    arr_sq.append("update param_tbl set entryValue='%s' where groupPath='software.param' and entryName='version'" %(str(version["param"])))
    arr_sq.append("update param_tbl set entryValue='%s' where groupPath='software.build' and entryName='code'" %(str(version["code"])))

    arr_sq.append("update param_tbl set entryValue='%s' where groupPath='software.mysql' and entryName='path'" %str(MYSQL['PATH']))
    arr_sq.append("update param_tbl set entryValue='%s' where groupPath='software.mysql' and entryName='port'" %str(MYSQL['PORT']))
    arr_sq.append("update param_tbl set entryValue='%s' where groupPath='software.mysql' and entryName='root_pw'" %str(MYSQL['PASS']))
    
    arr_sq.append('commit')
    

    # delete unnecessary
    sq = "select * from param_tbl"
    cur.execute(sq)
    rows = cur.fetchall()
    for row in rows:
        if not (row[1] + '.' + row[2]  in arr_grps) :
            arr_sq.append('delete from param_tbl where prino=%d' %row[0])

    for i, sq in enumerate(arr_sq):
        prints(sq)
        progress((i+1), len(arr_sq), "patchParamDB")
        if sq == 'commit':
            dbsqcon.commit()
            continue
        cur.execute(sq)
    
    dbsqcon.close()
    if _SERVER_MAC != mac :    
        # os.unlink(fname_ini)
        pass
    prints("patching Param DB Finished")
    print()


def migrateParam():
    global _ROOT_DIR
    fname_db  = "%s/bin/param.db" %_ROOT_DIR
    prints ("migrate Param tbl from old config tbl as version 0.5 or below")
    arr_sq = list()
    dbsqcon = sqlite3.connect(fname_db)
    with dbsqcon:
        cur =  dbsqcon.cursor()
        sq = "select entryValue from config_tbl where entryName='flag' and section='migrate'"
        try:
            cur.execute(sq)
            rs = cur.fetchone()
        except:
            prints("No table name for config_tbl")
            progress(100, 100, "migrateParamDB")
            return True

        if rs == None :
            sq = "insert into config_tbl (entryName, entryValue, section) values('flag', 'no', 'migrate')"
            cur.execute(sq)
            dbsqcon.commit()
        sq ="select entryValue from config_tbl where section='migrate' and entryName='flag'"
        cur.execute(sq)
        rs = cur.fetchone()
        if rs[0] == 'yes': # config table to param table
            prints("No needs to update param tbl from config_tbl")
            progress(100, 100, "migrateParamDB")
            return False

        sq = "select section, entryName, entryValue from config_tbl"
        cur.execute(sq)
        rows = cur.fetchall()
        for row in rows:
            if row[0] == 'LICENSE' and str(row[1])=='CODE' and row[2]:
                arr_sq.append("update param_tbl set entryValue='%s' where groupPath='software.service.licesnse' and entryName='code'" %row[2])
            elif row[0] == 'ROOT' and row[1]=='DOCUMENT_TITLE' and row[2]:
                arr_sq.append("update param_tbl set entryValue='%s' where groupPath='software.root.webpage' and entryName='document_title'" %row[2])
            elif row[0] == 'ROOT' and row[1]=='HOST_TITLE':
                arr_sq.append("update param_tbl set entryValue='%s' where groupPath='software.root.webpage' and entryName='host_title'" %row[2])
            elif row[0] == 'ROOT' and row[1]=='LOGO_PATH' and row[2]:
                arr_sq.append("update param_tbl set entryValue='%s' where groupPath='software.root.webpage' and entryName='logo_path'" %row[2])
            elif row[0] == 'SERVICE' and row[1]=='MODE' and row[2]:
                arr_sq.append("update param_tbl set entryValue='%s' where groupPath='software.service' and entryName='mode'" %row[2])
            elif row[0] == 'SERVICE' and row[1]=='COUNT_EVENT' and row[2]:
                arr_sq.append("update param_tbl set entryValue='%s' where groupPath='software.service' and entryName='count_event'" %row[2].lower())
            elif row[0] == 'SERVICE' and row[1]=='COUNTING' and row[2]:
                arr_sq.append("update param_tbl set entryValue='%s' where groupPath='software.service' and entryName='counting'" %row[2].lower())
            elif row[0] == 'SERVICE' and row[1]=='FACE' and row[2]:
                arr_sq.append("update param_tbl set entryValue='%s' where groupPath='software.service' and entryName='face'" %row[2].lower())
            elif row[0] == 'SERVICE' and row[1]=='MACSNIFF' and row[2]:
                arr_sq.append("update param_tbl set entryValue='%s' where groupPath='software.service' and entryName='macsniff'" %row[2].lower())
            elif row[0] == 'SERVICE' and row[1]=='SNAPSHOT' and row[2]:
                arr_sq.append("update param_tbl set entryValue='%s' where groupPath='software.service' and entryName='snapshot'" %row[2].lower())
            elif row[0] == 'SERVICE' and row[1]=='PROBE_INTERVAL' and row[2]:
                arr_sq.append("update param_tbl set entryValue='%d' where groupPath='software.service' and entryName='probe_interval'" %(int(row[2])))
            elif row[0] == 'MYSQL' and row[1]=='RECYCLING_TIMESTAMP' and row[2]:
                if row[2] =='30days':
                    recycling_time = 30*3600*24
                elif row[2] =='60days':
                    recycling_time = 60*3600*24
                elif row[2] =='90days':
                    recycling_time = 90*3600*24
                else:
                    recycling_time = 365*3600*24
                arr_sq.append("update param_tbl set entryValue='%d' where groupPath='software.mysql' and entryValue='recycling_time'" %recycling_time)
            elif row[0] == 'FPP' and row[1]=='HOST' and row[2]:
                arr_sq.append("update param_tbl set entryValue='%s' where groupPath='software.fpp' and entryValue='host'" %row[2])
            elif row[0] == 'FPP' and row[1]=='API_KEY' and row[2]:
                arr_sq.append("update param_tbl set entryValue='%s' where groupPath='software.fpp' and entryValue='api_key'" %row[2])
            elif row[0] == 'FPP' and row[1]=='API_SRCT' and row[2]:
                arr_sq.append("update param_tbl set entryValue='%s' where groupPath='software.fpp' and entryValue='api_srct'" %row[2])
        
        arr_sq.append("update config_tbl set entryValue='yes' where section='migrate' and entryname='flag'")
        for i, sq in enumerate(arr_sq):
            prints (sq)
            cur.execute(sq)
            progress((i+1), len(arr_sq), "migrateParamDB")

        dbsqcon.commit()
        prints("Param table migration finished")
        print()








########################################################################################################################################################
########################   MYSQL //  MariaDB  ##########################################################################################################
#sc create MariaDB binpath= E:\Cosilan\Mariadb\bin\mysqld.exe
def getMysqlPort():
    if os.name !='nt':
        return 3306
    c_sec = False
    if not os.path.isfile(_ROOT_DIR + "/MariaDB/data/my.ini"):
        return 3306

    with open (_ROOT_DIR + "/MariaDB/data/my.ini", "r") as f:
        body = f.read()

    for line in body.splitlines():
        if not line.strip()  or line.strip()[0] == '#':
            continue
        if line.strip()[0] == "[" :
            c_sec = True if line.strip() == "[mysqld]" else False

        if c_sec and line.strip().startswith("port"):
            mysql_port = int(line.split("=")[1].strip())
            return mysql_port
    return False

def dbconMaster(host='', user='', password='',  charset = 'utf8', port=3306): #Mysql
    global MYSQL
    if not host:
        host=MYSQL['HOST']
    if not user :
        user = MYSQL['USER']
    if not password:
        password = MYSQL['PASS']

    try:
        dbcon = pymysql.connect(host=host, user=str(user), password=str(password),  charset=charset, port=port)
    except pymysql.err.OperationalError as e :
        print (str(e))
        return None
    return dbcon   

def checkVersion():
    global _SERVER_IP
    dbConRemote = dbconMaster(host=_SERVER_IP, user = 'rt_user', password = '13579',  charset = 'utf8', port=3306)
    dbConLocal = dbconMaster(port=_mysql_port)
    arr_list = list()
    
    with dbConRemote:
        cur = dbConRemote.cursor()

# Caption     CommandLine  CreationClassName  CreationDate               CSCreationClassName   CSName  Description  ExecutablePath  ExecutionState  Handle  HandleCount  InstallDate  KernelModeTime  MaximumWorkingSetSize  MinimumWorkingSetSize  Name        OSCreationClassName    OSName                                                            OtherOperationCount  OtherTransferCount  PageFaults  PageFileUsage  ParentProcessId  PeakPageFileUsage  PeakVirtualSize  PeakWorkingSetSize  Priority  PrivatePageCount  ProcessId  QuotaNonPagedPoolUsage  QuotaPagedPoolUsage  QuotaPeakNonPagedPoolUsage  QuotaPeakPagedPoolUsage  ReadOperationCount  ReadTransferCount  SessionId  Status  TerminationDate  ThreadCount  UserModeTime  VirtualSize  WindowsVersion  WorkingSetSize  WriteOperationCount  WriteTransferCount
# mysqld.exe               Win32_Process      20220507202850.395913+480  Win32_ComputerSystem  H-PC    mysqld.exe                                   4436    169                       1250000                                                       mysqld.exe  Win32_OperatingSystem  Microsoft Windows 10 Pro|C:\Windows|\Device\Harddisk0\Partition3  1064                 21898               139119      4726388        676              4795784            9299783680       408192              8         4839821312        4436       24                      110                  72                          110                      288                 5421345            0                                   36           2031250       9241493504   10.0.19044      353406976       1809                 2149029

# AcceptPause  AcceptStop  Caption  CheckPoint  CreationClassName  DelayedAutoStart  Description              DesktopInteract  DisplayName  ErrorControl  ExitCode  InstallDate  Name     PathName                                                                            ProcessId  ServiceSpecificExitCode  ServiceType  Started  StartMode  StartName                    State    Status  SystemCreationClassName  SystemName  TagId  WaitHint
# TRUE         TRUE        MariaDB  0           Win32_Service      FALSE             MariaDB database server  FALSE            MariaDB      Normal        0                      MariaDB  "E:\MariaDB10\bin\mysqld.exe" "--defaults-file=E:\MariaDB10\data\my.ini" "MariaDB"  4436       0                        Own Process  TRUE     Auto       NT AUTHORITY\NetworkService  Running  OK      Win32_ComputerSystem     H-PC        0      0

def statusMysql(user='', password=''):
    arr =  []
    cmd = """wmic process where name='mysqld.exe' get executablepath"""
    p = os.popen(cmd).read().upper()
    p += "\n"
    cmd = """wmic service where name='mariadb' get pathname"""
    p += os.popen(cmd).read().upper()

    # print(p)

    expected_dir = os.path.abspath(_ROOT_DIR + "/Mariadb/bin/").upper()
    for line in str(p).splitlines():
        tp = line.find("MYSQLD.EXE")
        if tp >0:
            line = line.replace('"', '')
            line = line[:tp+len("MYSQLD.EXE")].strip()

            a_path = os.path.dirname(os.path.abspath(line))
            arr.append({'path_flag': expected_dir == a_path, 'execute_path': a_path, 'port':0, 'version':'', 'uptime':'', 'running':False})

    if not user:
        user = MYSQL['USER']
    if not password:
        password = MYSQL['PASS']

    for i in range(len(arr)):
        os.chdir(arr[i]['execute_path'])
        cmd = "mysqladmin -u%s -p%s version" %(MYSQL['USER'], MYSQL['PASS'])
        p = os.popen(cmd).read().upper()
        for line in str(p).splitlines():
            line = line.strip()
            if line.startswith("SERVER VERSION"):
                arr[i]['version'] = line.split("\t")[-1].strip()
            elif line.startswith("UPTIME:"):
                arr[i]['uptime'] = line.split("\t")[-1].strip()
                arr[i]['running'] = True
            elif line.startswith("TCP PORT"):
                arr[i]['port'] = int(line.split("\t")[-1].strip())
    prints(arr)
    return arr

def killMysql(working_dir='', user='', passwd=''):
    if not user:
        user = MYSQL['USER']
    if not passwd:
        passwd = MYSQL['PASS']
    if not working_dir:
        working_dir = _ROOT_DIR
    os.chdir(working_dir + "\\MariaDB\\bin")
    a = os.popen("mysqladmin -u% -p%s shutdown" %(user, passwd))
    p = a.read()
    print (p)

def killNginx():
    os.chdir(_ROOT_DIR + "\\Nginx")
    a = os.popen("nginx.exe -s quit")
    p = a.read()
    print (p)

# def checkMaraiaDBPath():
#     if not os.path.exists(_ROOT_DIR + "\\MariaDB\\bin\\RunHiddenConsole.exe"):
#         a = os.system("copy %s\\bin\\RunHiddenConsole.exe %s\\MariaDB\\bin\\" %(_ROOT_DIR, _ROOT_DIR))    

#     cmd = """wmic process where name='mysqld.exe' get executablepath"""
#     p = os.popen(cmd).read().upper()

#     if p.find("MYSQLD.EXE") > 0  and p.find(_ROOT_DIR.upper()) < 0 :
#         print ("Mysqld running on different (wrong, previous) location")
#         print ("Kill Process startBI, mysqld, nignx, php ")
#         a = os.popen("taskkill /F /IM startBI.exe > nul")
#         a = os.popen("""wmic process where "name='python.exe' or name='python3.exe'" get caption, processid, commandline""")
#         lines =(a.read()).splitlines() 
#         pid = 0
#         for line in lines:
#             if line.lower().find("startbi.py") >0:
#                 tabs=line.split(" ")
#                 for tab in tabs: 
#                     if not tab.strip():
#                         continue
#                     try:
#                         pid = int(tab)
#                     except:
#                         continue
#         if pid:
#             a = os.popen("taskkill /F /PID %d > nul" %pid)
#         a = os.popen("taskkill /F /IM mysqld.exe > nul")
#         a = os.popen("taskkill /F /IM nginx.exe > nul")
#         # a = os.popen("%s/Mariadb/bin/mysqladmin -uroot -prootpass shutown" %_ROOT_DIR)
#         a = os.popen("taskkill /F /IM php-cgi.exe > nul")
#         for i in range (0,10):
#             p = os.popen(""" wmic process where name='mysqld.exe' get executablepath """).read()
#             if p.find("mysqld.exe") <0:
#                 break
#             time.sleep(1)

#     if p.find("mysqld.exe") < 0 :
#         os.chdir(_ROOT_DIR + "\\MariaDB\\bin")
#         a = os.system("start RunHiddenConsole.exe mysqld.exe")	
#         log.info("Mysqld Startd")
#         time.sleep(5)

#     cmd = """wmic process where name='mysqld.exe' get executablepath"""
#     print ("mysql is running at " + str(os.popen(cmd).read()))

def repairMariadb():
    global _ROOT_DIR
    if os.name !=  'nt':
        return False
    
    print ("Repair Mysql DB / Maria DB ")
    a = os.popen("taskkill /F /IM mysqld.exe > nul")
    p = a.read()
    time.sleep(2)
    if MYSQL_VERSION.startswith("10.4.12"):
        fname = _ROOT_DIR +'/Mariadb/mysql_data_mysql_db.zip'
        path = _ROOT_DIR + '/MariaDB/data/mysql/'
        zf = zipfile.ZipFile(fname,'r')
        for fname in zf.namelist():
            zf.extract(fname, path)
        zf.close()

    for a,b,c in os.walk(_ROOT_DIR + "/Mariadb/data/"):
        if a.endswith("/data/"):
            files = c
            
    for file in files:
        if file == 'ibdata1' or file == 'my.ini' or  file == "mysql.ibd":
            continue
        fname = _ROOT_DIR+"/Mariadb/data/"+file
        os.unlink(fname)
        print(fname,"  deleted")

    os.chdir(_ROOT_DIR + "/MariaDB/bin")
    a = os.system("start RunHiddenConsole.exe mysqld.exe")	
    log.info("Mysqld Startd")
    time.sleep(5)


def patchMariaDB():
    prints ("Patching Maria DB")
    if is_online(_SERVER_IP) == False:
        prints("Cannot reach mysql server", "error")
        return False
    
    dbConRemote = dbconMaster(host=_SERVER_IP, user = 'rt_user', password = '13579',  charset = 'utf8', port=3306)
    dbConLocal = dbconMaster(port=MYSQL['PORT'])

    if dbConRemote == None:
        prints("Cannot reach remote mysql server!!", "error")
        return False
    
    if dbConLocal == None:
        prints("pleas check mysql or maria db running!!","error")
        return False        

    arrDatabase = ['common', 'cnt_demo']
    arrRemoteTables = list()
    arr_sq = list()

    regex_auto = re.compile('AUTO_INCREMENT=(\d+)', re.IGNORECASE)
    with dbConRemote:
        cur = dbConRemote.cursor()
        for db in arrDatabase:
            arr_sq.append("CREATE DATABASE IF NOT EXISTS `%s`" %db)
            arr_sq.append('commit')
            sq = "show tables from %s" %db
            cur.execute(sq)
            tables = cur.fetchall()
            for table_ in tables:
                table = table_[0]
                sq = "show create table %s.%s" %(db, table)
                cur.execute(sq)
                rows = cur.fetchall()
                for row in rows:
                    sql = row[1].replace("CREATE TABLE ", "CREATE TABLE IF NOT EXISTS `%s`." %db)
                    sql = sql.replace(regex_auto.search(sql).group(), "AUTO_INCREMENT=1")
                    arr_sq.append(sql)
                sq = "show fields from %s.%s" %(db, table)
                cur.execute(sq)
                rows = cur.fetchall()
                for row in rows:
                    arrRemoteTables.append((db, table, row))
            arr_sq.append("commit")

        arr_sq.append("CREATE USER IF NOT EXISTS 'admin'@'localhost' IDENTIFIED BY '13579';")
        arr_sq.append("CREATE USER IF NOT EXISTS 'ct_user'@'localhost' IDENTIFIED BY '13579';")
        arr_sq.append("CREATE USER IF NOT EXISTS 'rt_user'@'%' IDENTIFIED BY '13579';")
        
        if MYSQL['VERSION'].startswith('8.0'):
            arr_sq.append("ALTER USER 'admin'@'localhost' IDENTIFIED WITH mysql_native_password BY '13579';")
            arr_sq.append("ALTER USER 'ct_user'@'localhost' IDENTIFIED WITH mysql_native_password BY '13579';")
            arr_sq.append("ALTER USER 'rt_user'@'%' IDENTIFIED WITH mysql_native_password BY '13579';")

        if os.name == 'posix':
            arr_sq.append("UPDATE mysql.user SET plugin='auth_socket' WHERE User='admin';")
            arr_sq.append("UPDATE mysql.user SET plugin='mysql_native_password' where User='root';")
            arr_sq.append("UPDATE mysql.user SET plugin='mysql_native_password' where User='admin';")
            arr_sq.append("UPDATE mysql.user SET plugin='mysql_native_password' where User='ct_user';")
            arr_sq.append("UPDATE mysql.user SET plugin='mysql_native_password' where User='rt_user';")
            arr_sq.append("UPDATE mysql.user SET grant_priv='Y' where user='admin';")

        arr_sq.append("GRANT ALL PRIVILEGES ON *.* TO 'admin'@'localhost';")
        arr_sq.append("GRANT insert, select, update, delete, alter ON common.* TO 'ct_user'@'localhost';")
        arr_sq.append("GRANT insert, select, update, delete, alter ON cnt_demo.* TO 'ct_user'@'localhost';")
        arr_sq.append("GRANT select ON common.* TO 'rt_user'@'%';")
        arr_sq.append("GRANT select ON cnt_demo.* TO 'rt_user'@'%';")

        arr_sq.append("FLUSH PRIVILEGES;")
        # arr_sq.append("INSERT INTO common.users(regdate, code, ID, passwd, db_name, flag, role) VALUES (now(),'U000000000001','root','rootpass','cnt_demo','y','admin') ON DUPLICATE KEY UPDATE ID = VALUES(ID)")
        arr_sq.append("INSERT INTO common.users(regdate, code, ID, passwd, db_name, flag, role) select now(), 'U000000000001','root','rootpass','cnt_demo','y','admin' FROM DUAL WHERE NOT EXISTS(SELECT ID FROM common.users where ID='root')")
        arr_sq.append('commit')

        arr_sq.append("alter table common.params modify usn varchar(127);")
        arr_sq.append("alter table common.params modify product_id varchar(127);")
        arr_sq.append("alter table cnt_demo.camera modify usn varchar(127);")
        arr_sq.append("alter table cnt_demo.camera modify product_id varchar(127);")
        arr_sq.append('commit')

    with dbConLocal:
        cur = dbConLocal.cursor()
        for i, sq in enumerate(arr_sq):
            prints (sq)
            if sq == 'commit':
                dbConLocal.commit() 
            else :
                try:
                    cur.execute(sq)
                except Exception as e:
                    prints(str(e))
            progress((i+1), len(arr_sq), "patchMariaDB")

        for tbl in arrRemoteTables:
            sq = "show fields from %s.%s like '%s'" %(tbl[0], tbl[1], tbl[2][0])
            cur.execute(sq)
            rs = cur.fetchall()
            if not rs:
                default = "" if tbl[2][4] == None else "default '%s'" %(str(tbl[2][4]))
                sq = "alter table %s.%s add %s %s %s" %(tbl[0], tbl[1], tbl[2][0], tbl[2][1],  default )
                prints (sq)
                cur.execute(sq)
        dbConLocal.commit()
        print()
        print ("""
If you want to use remote access, edit my.ini in windows or /etc/mysql/mariadb.conf.d/50-server.cnf in linux, nand block bind-address like #bind-address=localhost
If you have flush privileges Error "mysqlcheck -r mysql tables_priv -u root -p", "mysqlcheck -u root -p --auto-repair -c -o --all-databases
If you have trouble in runnig mysqld, delete files in data except ***ibdata1*** and my.ini or my.cnf
Got error 176 "Read page with wrong checksum" from storage engine Aria
        """)



def patchLanguage():
    prints ("Patching Language pack")

    if is_online(_SERVER_IP) == False:
        prints("Cannot reach remote server", "error")
        return False

    dbConRemote = dbconMaster(host=_SERVER_IP, user = 'rt_user', password = '13579',  charset = 'utf8')
    dbConLocal = dbconMaster(port=MYSQL['PORT'])

    if dbConRemote == None:
        prints("Cannot reach remote mysql server!!", "error")
        return False
    
    if dbConLocal == None:
        prints("pleas check mysql or maria db running!!", "error")
        return False        

    arr_list = list()
    arr_sq = list()
    
    with dbConRemote:
        cur = dbConRemote.cursor()
        sq = "select varstr, eng, chi, kor, page from cnt_demo.language "        
        cur.execute(sq)
        rows = cur.fetchall()
        for row in rows:
            arr_list.append(row)

    with dbConLocal:
        cur = dbConLocal.cursor()
            
        for i, lang in enumerate(arr_list):
            sq = "select pk from common.language  where varstr='%s' and eng='%s' and chi='%s' and kor='%s' and page='%s'"  %(lang)
            cur.execute(sq)
            rows = cur.fetchone()

            if (rows==None):
                sq = "insert into common.language(varstr, eng, chi, kor, page) values('%s', '%s', '%s', '%s', '%s')" %(lang)
                arr_sq.append(sq)

            sq = "select pk from cnt_demo.language  where varstr='%s' and eng='%s' and chi='%s' and kor='%s' and page='%s'"  %(lang)
            cur.execute(sq)
            rows = cur.fetchone()
            if (rows==None):
                sq = "insert into cnt_demo.language(varstr, eng, chi, kor, page) values('%s', '%s', '%s', '%s', '%s')" %(lang)
                arr_sq.append(sq)

        if(arr_sq) :
            arr_sq.append('commit')


        for i, sq in enumerate(arr_sq):
            prints (sq)
            if sq == 'commit':
                dbConLocal.commit() 
            else :
                try:
                    cur.execute(sq)
                except Exception as e:
                    prints(str(e))
            progress((i+1), len(arr_sq), "patchLanguage")
        progress(100, 100, "patchLanguage")
    print()

def patchWebConfig():
    prints ("Patching Webpge Config")

    if checkAvailabe() == False:
        prints("Cannot reach remote mysql server!!", "error")
        return False
    dbConRemote = dbconMaster(host=_SERVER_IP, user = 'rt_user', password = '13579',  charset = 'utf8', port=3306)
    dbConLocal = dbconMaster(port=MYSQL['PORT'])

    if dbConRemote == None:
        prints("Cannot reach remote mysql server!!", "error")
        return False
    
    if dbConLocal == None:
        print("pleas check mysql or maria db running!!", "error")
        return False        

    arr_list = list()
    arr_db = list()
    arr_sq = list()
    
    with dbConRemote:
        cur = dbConRemote.cursor()
        sq = "select page, frame, depth, pos_x, pos_y, body, flag from cnt_demo.webpage_config "        
        cur.execute(sq)
        rows = cur.fetchall()
        for row in rows:
            arr_list.append(row)

    with dbConLocal:
        cur = dbConLocal.cursor()
        sq = "show databases"
        cur.execute(sq )
        rows = cur.fetchall()
        for row in rows:
            if row[0] in ['common','information_schema', 'mysql', 'performance_schema', 'test'] :
                continue
            arr_db.append(row[0])
        prints(arr_db)
        for db_name in arr_db:
            for i, web_config in enumerate(arr_list):
                sq = "select pk from "+db_name+".webpage_config where page='%s' and frame='%s' and depth='%s' and pos_x='%s' and pos_y='%s'" %web_config[:5]
                cur.execute(sq )
                rows = cur.fetchone()

                if (rows==None or not rows):
                    arr = list(web_config)
                    arr[5] = re.escape(arr[5])
                    sq = "insert into "+db_name+".webpage_config(page, frame, depth, pos_x, pos_y, body, flag) values('%s', '%s', '%s', '%s','%s','%s', '%s')" %(tuple(arr))
                    arr_sq.append(sq)
        
        if(arr_sq) :
            arr_sq.append('commit')
        
        for i, sq in enumerate(arr_sq):
            prints (sq)
            if sq == 'commit':
                dbConLocal.commit() 
            else :
                try:
                    cur.execute(sq)
                except Exception as e:
                    prints(str(e))
            progress((i+1), len(arr_sq), "patchWebConfig")
        progress(100, 100, "patchWebConfig")

    print()

# patchWebConfig()

# sys.exit()




        



       




def register_auto_start(flag):
    file_ex = '"%s\\bin\\start.bat" ' %(_ROOT_DIR)
    key = winreg.OpenKey(winreg.HKEY_CURRENT_USER,'Software\Microsoft\Windows\CurrentVersion\Run',0,winreg.KEY_SET_VALUE)
    if flag == 'yes' or flag==1 : 
        winreg.SetValueEx(key,'startBI',0,winreg.REG_SZ, file_ex) 
        print ("Register to auto start up")
    else :
        try:
            winreg.DeleteValue(key,'startBI') 
        except :
            pass
        print ("Cancel from auto start up")
    
    key.Close()



########################################################################################################################################################
##############################################  Windows GUI ############################################################################################
########################################################################################################################################################

def loadLangPack():
	LOCALE = locale.getdefaultlocale()
	if LOCALE[0] == 'zh_CN':
		key = 'Chinese'
	elif LOCALE[0] == 'ko_KR':
		key = 'Korean'
	else :
		key = 'English'
	# key = 'Chinese'

	arr_lang = {}
	fname = _ROOT_DIR + "/bin/update_main.json"
	if not os.path.isfile(fname):
		return False
	with open(fname, 'r', encoding='utf-8') as f:
		lang_json = f.read()

	for r in json.loads(lang_json)['language']:
		arr_lang[r['key']] = r[key]
	
	return arr_lang


def checkAvailabe():
    x = is_online(_SERVER_IP, port=80)
    if x:
        prints("Server is Online")
        if _WIN_GUI:
            var['tex_server_state'].set("OnLine")
        else:
            strn = "Online"
    else :
        prints("Server is Offline")
        if _WIN_GUI:
            var['tex_server_state'].set("OffLine")
        else:
            strn = "Offline"

        return False

    mac = "%012X" %(uuid.getnode())
    if _SERVER_MAC == mac :
        prints("Server MAC and machine MAC are the same", "error")
        if _WIN_GUI:
            var['tex_server_state'].set("Unavailable")
        else:
            strn = "Unavailable"

        return False
    else :
        prints("Server MAC and machine MAC are not the same")
        if _WIN_GUI:
            var['tex_server_state'].set("Available")
        else:
            strn = "Available"

    # _MyPublicIP = getMyPublicIP()
    # if _SERVER_IP == _MyPublicIP:
    #     prints ("SERVER IP and LOCAL MACHINE IP is the same, cannot updated.", "error")
    #     var['tex_server_state'].set("Unavailable")
    #     return False
    # else:
    #     prints ("SERVER IP and LOCAL MACHINE IP are not the same.")
    #     var['tex_server_state'].set("Available")
    
    return strn

def progress(current, total, target):
    # left:20, down:18, right:19. up:17 : ascii decimal
    p = int(current * 100 / total) if total  else 0
    if os.name == 'nt' and _WIN_GUI :
        prog[target]["value"] = p
        prog[target].update()
    else :
        line = ""
        for i in range(0, 100, 2) :
            line += "=" if (i <= p) else " "

        print("\r %d%%    [%s]" %(int(p), line), end="")

def prints(strs, cls="info"):
    if type(strs) is list or type(strs) is dict:
        strs = json.dumps(strs)
    strs = str(strs)

    print(strs)
    if os.name == 'nt' and _WIN_GUI and window :
        tx.insert("end", strs + "\n")
        if cls == "error" :
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

def infoBox(pad=None, serverSt=""):

    label = ["txt_version_bin", "txt_version_webpage", "txt_version_param", "txt_version_code", "tex_server_state"]
    strn  = [version['bin'], version['webpage'], version['param'], version['code'], serverSt]

    if _WIN_GUI:
        for i, l in enumerate(label):
            var[l] = StringVar()
            var[l].set(strn[i])
            Label(pad, text=lang[l]).grid(row=0, column=i, sticky="news", ipadx=5)
            Label(pad, textvariable=var[l], width=1, bg="#E0E5E5").grid(row=1, column=i, sticky="news", ipadx=5)
  
    else :
        strs = [""]*4
        strs[0]  = "======================================================================================================="
        for i, l in enumerate(label):
            strs[1] += "%-20s " %lang[l]
            strs[2] += "%-20s " %strn[i]
        strs[3]  = "======================================================================================================="
        prints ("\n".join(strs))


def progressPad(pad) :
    for i, l in enumerate(progLabel):
        if not l in lang:
            lang[l] = l

        Label(pad, text=lang[l], anchor="w").grid(row=i, column=0, pady=2, sticky="w", ipadx=2)
        prog[l] = ttk.Progressbar(pad, maximum=100, length=410, mode="determinate")
        prog[l].grid(row=i, column=1)
        prog[l]["value"] = 0    

def buttonPad(pad):
    global btnStart
    arr = ["start", "cancel"]
    for l in arr:
        if not l in lang:
            lang[l] = l

    btnStart = Button(pad, text=lang["start"],  command=startUpdate, width=6, height=1)
    btnStart.pack(side="left", padx=5)
    Button(pad, text=lang["cancel"], command=cancel, width=6, height=1).pack(side="left", padx=5)



def cancel():
    window.destroy()
    pass


def windowsGUI(window):
    global tx
    window.protocol("WM_DELETE_WINDOW", window.destroy)
    window.title("%s %.2f" %(lang['main_title'], version['update']))
    window.geometry("600x600")
    window.resizable(True, True)

    frInfo = Frame(window, bd=0, relief="solid")
    frInfo.pack(side="top", expand=False, padx=10, pady=10)
    infoBox(frInfo)

    progPad = Frame(window, bd=0, relief="solid")
    progPad.pack(side="top", expand=False, padx=10, pady=10)
    progressPad(progPad)

    btnPad = Frame(window, bd=0, relief="solid")
    btnPad.pack(side="top", expand=False, padx=10, pady=10)
    buttonPad(btnPad)
    

    tx=Text(window, height=20, width=70)
    tx.pack(side="top", pady=5)

    prints("Update Tool for Windows")


def startUpdate():
    if _WIN_GUI:
        global btnStart
        for label in progLabel:
            progress(0, 0, label)
        btnStart['state'] = "disabled"
        patchHtml()
        patchBin()
        patchParamDb()
        migrateParam()
        patchMariaDB()
        patchLanguage()
        patchWebConfig()
        makeLink()
        btnStart['state'] = "normal"
    
    else :
        ServerSt = checkAvailabe()
        infoBox(None, ServerSt)
        patchHtml()
        # patchBin()
        # patchParamDb()
        # migrateParam()
        # patchMariaDB()
        # patchLanguage()
        # patchWebConfig()
        # makeLink()




def patchLangPack():
    global lang
    for r in lang:
        try:
            var[r].set(lang[r])
        except:
            pass

def alert(msg):
    global window
    subwin = Toplevel(window)
    subwin.title("Alert")
    subwin.geometry("400x100")
    subwin.resizable(True, True)
    Label(subwin, text=msg, anchor="center").pack(side="top", padx=10, expand=False)




if __name__ == '__main__':
     
    _mysql_port = getMysqlPort()
    lang = loadLangPack()
    window = None
    btnStart = None

    root_pw = configVars("software.mysql.root_pw")
    if root_pw:
        MYSQL['PASS'] = root_pw

    MYSQL['RUNNING'] = False
    embedded_mysql = configVars("software.mysql.embedded")
    my_st = statusMysql()
    for dt in my_st:
        if embedded_mysql == 'yes' and not dt['path_flag']:
            MYSQL['PATH']    = dt['execute_path']
            MYSQL['PORT']    = dt['port']
            MYSQL['VERSION'] = dt['version']
            MYSQL['UPTIME']  = dt['uptime']
            MYSQL["RUNNING"] = dt['running']

    print(MYSQL)

    var = {}
    if not _WIN_GUI :
        startUpdate()
        sys.exit()

    if _WIN_GUI :
        window = Tk()
        progLabel = ["patchHTML", "patchBinary", "patchParamDB",  "migrateParamDB", "patchMariaDB", "patchLanguage", "patchWebConfig"]
        prog = dict()
        var = dict()
        tx = None

        windowsGUI(window)
        var['tex_server_state'].set("Offline")
        checkAvailabe()


        if not MYSQL['RUNNING']:
            prints("MYSQL is not Running", "error")
            alert("MYSQL is not Running")
            btnStart['state'] = "disabled"

        window.mainloop()


    
    


