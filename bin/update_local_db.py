import sys, os, time
import pymysql
import sqlite3
import re
import json
import uuid
from update_main import _ROOT_DIR,  _CUSTOM_DB, patchParamDb, getCustomDBs

MYSQL = {'HOST':'localhost', 'USER':'root', 'PASS':'rootpass','PATH':'', 'PORT':0, 'VERSION':'', 'UPTIME':'', 'RUNNING':False}

def dbconMaster(host='', user='', password='',  charset = 'utf8', port=0): #Mysql
    global MYSQL
    if not host:
        host=MYSQL['HOST']
    if not user :
        user = MYSQL['USER']
    if not password:
        password = MYSQL['PASS']
    if not port:
        port = MYSQL['PORT']

    try:
        dbcon = pymysql.connect(host=host, user=str(user), password=str(password),  charset=charset, port=port)
    except pymysql.err.OperationalError as e :
        print (str(e))
        return None
    return dbcon   


# def getCustomDBs():
#     arr_db = set()
#     arr_tbl = set()
#     dbcon = dbconMaster()
#     with dbcon:
#         cur = dbcon.cursor()
#         sq = "SHOW DATABASES WHERE `Database` != 'information_schema' and `Database` != 'mysql' and `Database` != 'performance_schema' and `Database` != 'cnt_demo'" 
#         cur.execute(sq)
#         for db in cur.fetchall():
#             arr_tbl.clear()
#             sq = "show tables from %s" %db[0]
#             cur.execute(sq)
#             for tbl in cur.fetchall():
#                 arr_tbl.add(tbl[0])
#             print(arr_tbl)
#             if 'square' in arr_tbl and 'store' in arr_tbl and 'camera' in arr_tbl:
#                 arr_db.add(db[0])

#     return list(arr_db)


def patchLanguage():
    arr_list = list()
    arr_sq = list()
    arr_db = getCustomDBs(1)
    dbcon = dbconMaster()
    with dbcon:
        cur = dbcon.cursor()
        sq = "select varstr, eng, chi, kor, page from cnt_demo.language "        
        cur.execute(sq)
        rows = cur.fetchall()
        for row in rows:
            arr_list.append(row)

        for db in arr_db:
            for lang in arr_list:
                sq = "select pk from %s.language  where varstr='%s' and page='%s'"  %(db, lang[0], lang[4])
                cur.execute(sq)
                rows = cur.fetchone()
                if (rows==None):
                    sq = "insert into " + db + ".language(varstr, eng, chi, kor, page) values('%s', '%s', '%s', '%s', '%s')" %(lang)
                    arr_sq.append(sq)

        if(arr_sq) :
            arr_sq.append('commit')
    
        for sq in arr_sq:
            print (sq)
            if sq == 'commit':
                dbcon.commit() 
            else :
                try:
                    cur.execute(sq)
                except Exception as e:
                    print(str(e))


def patchWebConfig():
    arr_list = list()
    arr_sq = list()
    arr_db = getCustomDBs(1)
    dbcon = dbconMaster()
    with dbcon:
        cur = dbcon.cursor()        
        sq = "select page, frame, depth, pos_x, pos_y, body, flag from cnt_demo.webpage_config "        
        cur.execute(sq)
        rows = cur.fetchall()
        for row in rows:
            arr_list.append(row)     
        
        regex_auto = re.compile('AUTO_INCREMENT=(\d+)', re.IGNORECASE)
        for db in arr_db:
            sq = "show tables from %s like 'webpage_config'" %db
            cur.execute(sq)
            rows = cur.fetchone()
            print (sq, rows)
            if (rows==None or not rows):
                sq = "show create table cnt_demo.webpage_config"
                cur.execute(sq)
                row = cur.fetchone()
                sq = row[1].replace("CREATE TABLE ", "CREATE TABLE IF NOT EXISTS `%s`." %db)
                sq = sq.replace(regex_auto.search(sq).group(), "AUTO_INCREMENT=1")
                cur.execute(sq)
                dbcon.commit()

            for web_config in arr_list:
                sq = "select pk from " + db + ".webpage_config where page='%s' and frame='%s' and depth='%s' and pos_x='%s' and pos_y='%s'" %web_config[:5]
                cur.execute(sq)
                rows = cur.fetchone()
                if (rows==None or not rows):
                    arr = list(web_config)
                    arr[5] = re.escape(arr[5])
                    sq = "insert into " + db + ".webpage_config(page, frame, depth, pos_x, pos_y, body, flag) values('%s', '%s', '%s', '%s','%s','%s', '%s')" %(tuple(arr))
                    arr_sq.append(sq)

        if(arr_sq) :
            arr_sq.append('commit')
    
        for sq in arr_sq:
            print (sq)
            if sq == 'commit':
                dbcon.commit() 
            else :
                try:
                    cur.execute(sq)
                except Exception as e:
                    print(str(e))


# def patchParamDb():
#     global _ROOT_DIR
#     fname_ini = "%s/bin/param_tbl.ini" %_ROOT_DIR
#     fname_db  = "%s/bin/param.db" %_ROOT_DIR
#     # print(fname_ini)
#     print ("patching  Param DB from %s" %fname_ini)
#     arr_list = list()
#     arr_sq = list()
#     arr_grps = list()
    
#     if not os.path.isfile(fname_ini):
#         print ("Error, File %s is not exist" %fname_ini)
#         return False

#     with open (fname_ini, "r", encoding='utf-8') as f:
#         body = f.read()

#     for line in body.splitlines():
#         line = line.strip()
#         if not line or line[0] == "#":
#             continue
#         line = line.replace("'", "&#039;")
#         arr = json.loads('['+line+']')
#         arr_list.append(tuple(arr))
#         arr_grps.append(arr[0])

#     dbsqcon = sqlite3.connect(fname_db)
#     cur = dbsqcon.cursor()
#     sq = """CREATE TABLE IF NOT EXISTS param_tbl (\
#         prino INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,\
#         groupPath TEXT,\
#         entryName TEXT,\
#         entryValue TEXT,\
#         description TEXT,\
#         datatype TEXT default 'sz',\
#         option TEXT,\
#         create_permission INTEGER default 7,\
#         delete_permission INTEGER default 7,\
#         update_permission INTEGER default 7,\
#         read_permission INTEGER default 7,\
#         readonly INTEGER default 0,\
#         writeonly INTEGER default 0,\
#         group1 TEXT,\
#         group2 TEXT,\
#         group3 TEXT,\
#         group4 TEXT,\
#         group5 TEXT,\
#         group6 TEXT,\
#         made TEXT,\
#         regdate NUMERIC\
#     )"""
#     arr_sq.append(sq)
#     arr_sq.append('commit')

#     sq = """CREATE TABLE IF NOT EXISTS info_tbl(\
#         prino INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,\
#         category TEXT,\
#         entryName TEXT,\
#         entryValue TEXT,\
#         description TEXT,\
#         regdate NUMERIC\
#     )"""

#     arr_sq.append(sq)
#     arr_sq.append('commit')

#     for r in arr_list:
#         # sq = "select * from sqlite_master where name='param_tbl'"
#         exp = r[0].split(".")
#         grps = ["", "", "", "", "", ""]
#         groupPath=""
#         for i, e in enumerate(exp):
#             grps[i] = e
#             if i < len(exp)-1:
#                 if groupPath:
#                     groupPath +="."
#                 groupPath += e

#         entryName = exp.pop()
#         sq = "select prino from param_tbl where groupPath='%s' and entryName='%s'" %(groupPath, entryName)
#         # print (sq)
#         cur.execute(sq)
#         row = cur.fetchone()
#         if (row == None):
#             sq  = "INSERT INTO param_tbl( groupPath, entryName, entryValue, datatype, option, description, group1, group2, group3, group4, group5, group6, readonly, writeonly, made,  regdate, create_permission, delete_permission, update_permission, read_permission) "
#             sq += "VALUES('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', %s, %s, '%s', %s, 0,0,7,7 )" %(groupPath, entryName, r[1], r[2], r[3], r[6], grps[0], grps[1], grps[2], grps[3], grps[4], grps[5], r[4], r[5], 'hanskim', int(time.time()))
#         else:
#             sq = "UPDATE param_tbl set datatype='%s', option='%s', description='%s', readonly='%s', writeonly='%s' where prino=%s" %(r[2], r[3], r[6], r[4], r[5], row[0])
#         arr_sq.append(sq)

#     arr_sq.append('commit')

#     # MAC
#     mac = "%012X" %(uuid.getnode())
#     arr_sq.append("update param_tbl set entryValue='%s' where groupPath='system.network.eth0' and entryName='hwaddr'" %mac)
    
#     # version
#     arr_sq.append("update param_tbl set entryValue='%s' where groupPath='software.bin' and entryName='version'" %(str(version["bin"])))
#     arr_sq.append("update param_tbl set entryValue='%s' where groupPath='software.webpage' and entryName='version'" %(str(version["webpage"])))
#     arr_sq.append("update param_tbl set entryValue='%s' where groupPath='software.param' and entryName='version'" %(str(version["param"])))
#     arr_sq.append("update param_tbl set entryValue='%s' where groupPath='software.build' and entryName='code'" %(str(version["code"])))

#     arr_sq.append("update param_tbl set entryValue='%s' where groupPath='software.mysql' and entryName='path'" %str(MYSQL['PATH']))
#     arr_sq.append("update param_tbl set entryValue='%s' where groupPath='software.mysql' and entryName='port'" %str(MYSQL['PORT']))
#     arr_sq.append("update param_tbl set entryValue='%s' where groupPath='software.mysql' and entryName='root_pw'" %str(MYSQL['PASS']))
    
#     arr_sq.append('commit')
    

#     # delete unnecessary
#     sq = "select * from param_tbl"
#     cur.execute(sq)
#     rows = cur.fetchall()
#     for row in rows:
#         if not (row[1] + '.' + row[2]  in arr_grps) :
#             arr_sq.append('delete from param_tbl where prino=%d' %row[0])

#     for i, sq in enumerate(arr_sq):
#         print(sq)
#         if sq == 'commit':
#             dbsqcon.commit()
#             continue
#         cur.execute(sq)
    
#     dbsqcon.close()
#     print("patching Param DB Finished")
#     print()



if __name__ == '__main__':
    print(_ROOT_DIR, _CUSTOM_DB)
    # print(getCustomDBs())
    # patchLanguage()
    # patchWebConfig()
    patchParamDb()