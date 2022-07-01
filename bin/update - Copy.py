import time, sys, os
import socket
import shutil
# import requests
from configparser import ConfigParser
from http.client import HTTPConnection
import json, re
import threading
import pymysql, sqlite3
import py_compile
import logging, logging.handlers
import optparse

_SERVER_IP = '49.235.119.5'
MYSQL = {'HOST':'', 'USER':'root', 'PASS':'rootpass'}

def checkAvailabe(_SERVER_IP = '49.235.119.5'):
    server = ("api.ipify.org", 80)
    conn = HTTPConnection(*server)
    conn.putrequest("GET", "/")
    conn.endheaders()
    _MyPublicIP = conn.getresponse().read().decode('utf-8')
    conn.close()
    print (_SERVER_IP, _MyPublicIP)
    if _SERVER_IP == _MyPublicIP:
        return False
    return True

def flat_args():
    cmd_str = ""
    for i, x in enumerate(sys.argv):
        if i >0:
            cmd_str +=" " +  x
    return cmd_str

def argument():
    p = optparse.OptionParser()
    p.add_option("-S", "--server", action="store", type="string", dest="SERVER")
    p.add_option("-H", "--host", action="store", type="string", dest="mysqlHOST")
    p.add_option("-U", "--user", action="store", type="string", dest="mysqlUSER")
    p.add_option("-P", "--passwd", action="store", type="string", dest="mysqlPASS")
    p.add_option("--nodb", action="store_true", dest="NoDbAnalysis")
    p.add_option("--nodownload", action="store_true", dest="NoDownload")
    p.add_option("--temp", action="store_true", dest="istempfile")

    opt, args = p.parse_args()
    return opt

def downloadFile(_SERVER_IP, fname):
    server = (_SERVER_IP, 80)
    conn = HTTPConnection(*server)
    conn.putrequest("GET", "/download.php?file=%s" %fname) 
    print ("/download.php?file=%s" %fname)
    # http://49.235.119.5/download.php?file=../cosilanRelease/bin/update.exe
    conn.endheaders()
    rs = conn.getresponse()
    filesize = int(rs.getheader("Content-Length"))
    if rs.status == 200:
        totalbits = 0
        with open(fname, 'wb') as f:
            while totalbits < filesize:
                data = rs.read(1024)
                totalbits = filesize if totalbits > filesize else  (totalbits +1024)
                percent = int(totalbits/filesize*100)
                    
                print("File %s Downloaded %dB..., %d%%" %(fname, totalbits, percent), end="\r")
                f.write(data)
            print ()

    conn.close()
    logstr = "File %s Downloaded" %fname
    log.info(logstr)

if __name__ == '__main__':
    opt = argument()
    if opt.SERVER:
        _SERVER_IP = opt.SERVER

    if not checkAvailabe(_SERVER_IP):
        print ("SERVER IP and LOCAL MACHINE IP is the same, cannot updated.")
        sys.exit(0)

    absdir = os.path.dirname(os.path.abspath(sys.argv[0]))
    os.chdir(absdir)
    print (os.getcwd())

    cmd_str =  flat_args()
    print (cmd_str)

    log = logging.getLogger()
    logging.basicConfig(
        filename = "log/update.log",
        format = "%(levelname)-8s  %(asctime)s %(module)s %(funcName)s %(lineno)s %(message)s %(threadName)s",
        level = logging.INFO
    )    

    if sys.argv[0].split("/")[-1] == 'tmp_update.py' or sys.argv[0].split("/")[-1] == 'tmp_update.exe':
        # p = os.popen("taskkill /F /IM update.exe > nul")
        # fname = "../bin/update.py"
        # downloadFile(fname)
        fname = "../colilanRelease/dbref_20201225"
        downloadFile(_SERVER_IP, fname)

    else:
        if os.path.isfile("update.py"):
            shutil.copy2("update.py", "tmp_update.py")
            os.system("C:/Python38_64/python.exe tmp_update.py " + cmd_str)
        if os.path.isfile("update.exe"):
            shutil.copy2("update.exe", "tmp_update.exe")
            os.system("tmp_update.exe " + cmd_str)


    if opt.mysqlHOST:
        MYSQL['HOST'] = opt.mysqlHOST
    if opt.mysqlUSER:
        MYSQL['USER'] = opt.mysqlUSER
    if opt.mysqlPASS:
        MYSQL['PASS'] = opt.mysqlPASS

    DBAnalysis = not opt.NoDbAnalysis
    DownFiles = not opt.NoDownload



    print ("done")
    sys.exit()


    

"""
1) rename udpate.exe or update.py to tmp_update.exe or tmp_update.py
2) execute tmp_update.exe or python3 tmp_update.py and exit on (old)update.exe(py)
3) on tmp_update, download new update if need, execuete new update and exit 
4) delete tmp_update and operate normally
"""







if cmd_str.find("--temp") >=0:
    p = os.popen("taskkill /F /IM update.exe > nul")
    # fname = "../bin/update.py"
    # downloadFile(fname)
    fname = "../cosilanRelease/bin/update.exe"
    downloadFile(fname)

else:
    if os.path.isfile("update.py"):
        shutil.copy2("update.py", "tmp_update.py")
        os.system("C:/Python38_64/python.exe tmp_update.py --temp " + cmd_str)
    if os.path.isfile("update.exe"):
        shutil.copy2("update.exe", "tmp_update.exe")
        os.system("tmp_update.exe --temp " + cmd_str)








exit()

if os.name == 'nt' :
    if args1 == '--delete':
        time.sleep(2)
        for i in range(5):
            p = os.popen("taskkill /F /IM temp_update.exe > nul")
            if p.read().find('not found') >0:
                break

        os.system('del temp_update.exe')
        sys.exit(0)

    cmd_str = ""
    for i, x in enumerate(sys.argv):
        if i >0:
            cmd_str +=" " +  x
    print (cmd_str)

    if sys.argv[0].split("/")[-1] == 'update.exe':
        os.system('copy update.exe temp_update.exe')
        os.system('temp_update.exe' + cmd_str )
        sys.exit(0)

    elif sys.argv[0].split("/")[-1] == 'temp_update.exe':
        p = os.popen("taskkill /F /IM update.exe > nul")

exit()

log = logging.getLogger()
logging.basicConfig(
    filename = "log/update.log",
    format = "%(levelname)-8s  %(asctime)s %(module)s %(funcName)s %(lineno)s %(message)s %(threadName)s",
    level = logging.INFO
)




p = optparse.OptionParser()
p.add_option("-S", "--server", action="store", type="string", dest="SERVER")
p.add_option("-H", "--host", action="store", type="string", dest="mysqlHOST")
p.add_option("-U", "--user", action="store", type="string", dest="mysqlUSER")
p.add_option("-P", "--passwd", action="store", type="string", dest="mysqlPASS")
p.add_option("--nodb", action="store_true", dest="NoDbAnalysis")
p.add_option("--nodownload", action="store_true", dest="NoDownload")

opt, args = p.parse_args()

print (opt, args)
if opt.SERVER:
    _SERVER_IP = opt.SERVER
if opt.mysqlHOST:
    MYSQL['HOST'] = opt.mysqlHOST
if opt.mysqlUSER:
    MYSQL['USER'] = opt.mysqlUSER
if opt.mysqlPASS:
    MYSQL['PASS'] = opt.mysqlPASS

DBAnalysis = not opt.NoDbAnalysis
DownFiles = not opt.NoDownload
print (_SERVER_IP, MYSQL)








def dbconMaster(): #Mysql
    try:
        dbconn0 = pymysql.connect(host = '', user = str(MYSQL['USER']), password = str(MYSQL['PASS']),  charset = 'utf8')
    except pymysql.err.OperationalError as e :
        print (str(e))
        return None
    return dbconn0


# def downloadFile(fname):
#     url = "http://%s/download.php?file=%s"  %(_SERVER_IP, fname)
#     print (url)
#     ret = requests.get( url, stream=True)
#     # fname = url.split("/")[-1]
#     totalbits = 0
#     if ret.status_code == 200:
#         with open(fname, 'wb') as f:
#             for chunk in ret.iter_content(chunk_size=1024):
#                 if chunk:
#                     totalbits += 1024
#                     print("Downloaded",totalbits,"B...", end="\r")
#                     f.write(chunk)

def downloadFile(fname):
    # url = "http://%s/download.php?file=%s"  %(_SERVER_IP, fname)
    # print (url)
    # ret = requests.get( url, stream=True)
    server = (_SERVER_IP, 80)
    conn = HTTPConnection(*server)
    conn.putrequest("GET", "/download.php?file=%s" %fname) 
    conn.endheaders()
    rs = conn.getresponse()
    filesize = int(rs.getheader("Content-Length"))
    if rs.status == 200:
        totalbits = 0
        with open(fname, 'wb') as f:
            while totalbits < filesize:
                data = rs.read(1024)
                totalbits = filesize if totalbits > filesize else  (totalbits +1024)
                percent = int(totalbits/filesize*100)
                    
                print("File %s Downloaded %dB..., %d%%" %(fname, totalbits, percent), end="\r")
                f.write(data)
            print ()

    conn.close()
    logstr = "File %s Downloaded" %fname
    log.info(logstr)

def compileFunctions():
    fname = "../bin/chkLic_s.py"
    downloadFile(fname)
    c = py_compile.compile('chkLic_s.py', 'chkLic.pyc')
    os.system("rm -rf " + fname.split('/')[-1])

    fname = "../bin/functions_s.py"
    downloadFile(fname)
    c = py_compile.compile('functions_s.py', 'functions.pyc')
    os.system("rm -rf " + fname.split('/')[-1])





def extractFilesTo(fname, dest, skip_files=[]):
    import tarfile
    logstr = "%s is tarfile? %r" %(fname, tarfile.is_tarfile(fname))
    print (logstr)
    log.info(logstr)

    fname_ = fname.split(".")
    if fname_[-1] == "tar":
        tar = tarfile.open(fname, "r:" )
    elif fname_[-1] == "gz":
        tar = tarfile.open(fname, "r:gz")
    else :
         return False
    
    for f in tar.getmembers():
        if f.name.split("/")[-1] in skip_files:
            logstr = "%s is not extracted , skipped" %fname
            print (logstr)
            log.info(logstr)
            continue
        logstr = "%s extract to %s" %(f.name, dest)
        print (logstr)
        log.info(logstr)
        tar.extract(f, dest)
    tar.close()

def getFilesInfo():
    # json_str = requests.get( "http://%s/download.php?check&os=%s" %(_SERVER_IP, os.name) ).text
    # json_str = requests.get( "http://%s/download.php?check&os=posix" %(_SERVER_IP) ).text
    server = (_SERVER_IP, 80)
    conn = HTTPConnection(*server)
    conn.putrequest("GET", "/download.php?check&os=%s" %os.name) 
    conn.endheaders()
    rs = conn.getresponse()
    json_str = rs.read()
    conn.close()

    arr_remote = json.loads(json_str)
    # print (arr_remote)
    targetDir = "../"
    arr_list = []
    for file_r in arr_remote:
        fname = file_r['name'].strip()
        fdate = ''; fsize = 0; ftimestamp = 0
        flag = 0  # 0: file, no change
        if fname[-1] == '/':
            flag = 3 # directory not existing
            if os.path.isdir(targetDir+fname):
                flag = 2  #directory existing
        elif (os.path.isfile(targetDir+fname)) :
            fsize = os.path.getsize(targetDir+fname)
            ftimestamp = int(os.path.getmtime(targetDir+fname)+3600*8)
            ftimestamp = ftimestamp - ftimestamp%60
            fdate = time.strftime("%Y-%m-%d %H:%M",time.gmtime(ftimestamp))
            if file_r['timestamp'] - ftimestamp > 0 :
                flag = 1 #file, changed

        else :
            flag = 5 # file, not existing
                
        arr_list.append({"name": fname, "date_remote": file_r['date'], "date_local": fdate, "size_remote": file_r['size'], "size_local": fsize, "timestamp_remote": file_r['timestamp'], "timestamp_local":ftimestamp, "modified": flag})

    return arr_list
        
            
def analyzeDatabase():
    log.info("Analizing Database")
    db_name = ''
    arr_list = [] #  [(db_name.table_name, field_name, field_desc), ] from db_ref
    arr_rs = [] #  [(db_name.table_name, field_name, field_desc), ] from mysql db
    arr_table = []
    table_regex = re.compile("CREATE TABLE `(.+?)` \((.+)\) (.+);", re.IGNORECASE)	
    with open("db_ref", "r", encoding="utf-8") as f:
        lines = f.readlines()
        str_t = ""
        for line in lines:
            line = line.strip()
            if not line or line[:2] == '--' :
                continue
            if line[:3] == "USE":
                db_name = line[5:-2]
                continue
            
            str_t += (line + '__LF__')
            if line[-1] == ';' :
                if str_t.startswith('CREATE TABLE'):
                    # print(); print (str_t)
                    table_desc = table_regex.search(str_t)
                    table = db_name +"." + table_desc.group(1)
                    desc = table_desc.group(2)
                    arr_table.append((table, desc.replace("__LF__", "").strip()))
                    tabs = desc.split("__LF__")
                    # print (tabs)
                    for tab in tabs:
                        tab = tab.strip()
                        if not tab:
                            continue
                        if tab.startswith("PRIMARY KEY ") or tab.startswith("KEY "):
                            continue
                        x = tab.replace('`','').strip()[:-1]
                        arr_list.append((table, x[:x.index(" ")], x[x.index(" "):].strip()))
                        
                str_t = ''
    # for arr in arr_table:
    #     print (arr)
    # for arr in arr_list:
    #     print (arr)
        
    dbconn0 = dbconMaster()
    with dbconn0:
        cur = dbconn0.cursor()
        for table, desc in arr_table:
            sq = "desc "+ table
            try:
                cur.execute(sq)
                rows = cur.fetchall()
                for row in rows:
                    arr_rs.append((table, row[0], row[1]))
            except Exception as e: #create table if not in mysql_db
                print (e)
                log.info(str(e))
                sq = "CREATE TABLE %s (%s)" %(table, desc)
                print (sq)
                cur.execute(sq)
                dbconn0.commit()
    
    # for arr in arr_rs:
    #     print (arr)  
    arr_sql = []
    for arr in arr_list:
        if not ((arr[0], arr[1]) in [(x[0], x[1]) for x in arr_rs]):
            print (arr[0], arr[1], arr[2], "does not exist")
            arr_sql.append("alter table %s add %s %s" %(arr[0], arr[1], arr[2]))
    # print (arr_sql)
    if (arr_sql) :
        dbconn0 = dbconMaster()
        with dbconn0:
            cur = dbconn0.cursor()
            for sql in arr_sql:
                # print (sql)
                log.info(sql)
                cur.execute(sql)
            dbconn0.commit()
    log.info("Analizing Database done")

def updateChanges():
    arr_modified_module = set()
    files = {"bin":"", "html": "", "etc":""}
    arr_list = getFilesInfo()


    for arr in arr_list:
        if arr['modified']&0x1 and not (arr["name"].split("/")[-1] in ["param.db", "config.ini", "config.org.ini"]):
            arr_modified_module.add(arr['name'].split("/")[0])
            print (arr)
            log.info(arr)
    print (arr_modified_module)
    if os.name == 'nt' and ("bin" in arr_modified_module):
        files["bin"] = "cosilanBinWin64.tar.gz"
        
    elif os.name == 'posix' and ("bin" in arr_modified_module):
        files["bin"] = "cosilanBinLinux.tar.gz"
        
    files["html"] = "cosilanHtmlfiles.tar.gz"
    
    print (files)
    if DownFiles:
        if files["bin"] :
            print ("downloading " + files["bin"])
            downloadFile(files["bin"])
        if files["html"]:
            print ("downloading " + files["html"])
            downloadFile(files["html"])
        if files["etc"]:
            print ("downloading " + files["etc"])
            downloadFile(files["etc"])

    if os.name == 'nt':
        if os.path.isfile(files["bin"]):
            p = os.popen("taskkill /F /IM Monitor.exe > nul")
            execute_monitor = False if p.read().find("not found") >= 0 else True
            p = os.popen("taskkill /F /IM startBI.exe > nul")
            print (p.read())
            time.sleep(1)
            extractFilesTo(files["bin"], "../", skip_files=["param.db", "config.ini", "config.org.ini"])
            time.sleep(1)
            p = os.system("RunHiddenConsole.exe startBI.exe")
            if execute_monitor:
                p = os.system("../monitor.exe")
            
        if os.path.isfile(files["html"]):
            extractFilesTo(files["html"], "../NGINX/")


    elif os.name=='posix' :
        if os.path.isfile(files["bin"]):
            extractFilesTo(files["bin"], "../", skip_files=["param.db", "config.ini", "config.org.ini"])
            p = os.system("systemctl restart BI")
            
        if os.path.isfile(files["html"]):
            extractFilesTo(files["html"], "../" )
        
        compileFunctions()


    
        


if __name__ == '__main__':
    log.info("Starting Update")
    print ("Starting Update")
    updateChanges()
    if DBAnalysis:
        analyzeDatabase()
    log.info("Complete Update")
    print ("Complete Update")

    if os.name == 'nt' and sys.argv[0].split("/")[-1] == 'temp_update.exe':
        os.system("update.exe --delete")
        sys.exit(0)
ble %s add %s %s" %(arr[0], arr[1], arr[2]))
    # print (arr_sql)
    if (arr_sql) :
        dbconn0 = dbconMaster()
        with dbconn0:
            cur = dbconn0.cursor()
            for sql in arr_sql:
                # print (sql)
                log.info(sql)
                cur.execute(sql)
            dbconn0.commit()
    log.info("Analizing Database done")

def updateChanges():
    arr_modified_module = set()
    files = {"bin":"", "html": "", "etc":""}
    arr_list = getFilesInfo()


    for arr in arr_list:
        if arr['modified']&0x1 and not (arr["name"].split("/")[-1] in ["param.db", "config.ini", "config.org.ini"]):
            arr_modified_module.add(arr['name'].split("/")[0])
            print (arr)
            log.info(arr)
    print (arr_modified_module)
    if os.name == 'nt' and ("bin" in arr_modified_module):
        files["bin"] = "cosilanBinWin64.tar.gz"
        
    elif os.name == 'posix' and ("bin" in arr_modified_module):
        files["bin"] = "cosilanBinLinux.tar.gz"
        
    files["html"] = "cosilanHtmlfiles.tar.gz"
    
    print (files)
    if DownFiles:
        if files["bin"] :
            print ("downloading " + files["bin"])
            downloadFile(files["bin"])
        if files["html"]:
            print ("downloading " + files["html"])
            downloadFile(files["html"])
        if files["etc"]:
            print ("downloading " + files["etc"])
            downloadFile(files["etc"])

    if os.name == 'nt':
        if os.path.isfile(files["bin"]):
            p = os.popen("taskkill /F /IM Monitor.exe > nul")
            execute_monitor = False if p.read().find("not found") >= 0 else True
            p = os.popen("taskkill /F /IM startBI.exe > nul")
            print (p.read())
            time.sleep(1)
            extractFilesTo(files["bin"], "../", skip_files=["param.db", "config.ini", "config.org.ini"])
            time.sleep(1)
            p = os.system("RunHiddenConsole.exe startBI.exe")
            if execute_monitor:
                p = os.system("../monitor.exe")
            
        if os.path.isfile(files["html"]):
            extractFilesTo(files["html"], "../NGINX/")


    elif os.name=='posix' :
        if os.path.isfile(files["bin"]):
            extractFilesTo(files["bin"], "../", skip_files=["param.db", "config.ini", "config.org.ini"])
            p = os.system("systemctl restart BI")
            
        if os.path.isfile(files["html"]):
            extractFilesTo(files["html"], "../" )
        
        compileFunctions()


    
        


if __name__ == '__main__':
    log.info("Starting Update")
    print ("Starting Update")
    updateChanges()
    if DBAnalysis:
        analyzeDatabase()
    log.info("Complete Update")
    print ("Complete Update")

    if os.name == 'nt' and sys.argv[0].split("/")[-1] == 'temp_update.exe':
        os.system("update.exe --delete")
        sys.exit(0)
