change_log = """
###############################################################################
queryDB.py

###############################################################################
"""
# print (change_log)

import os, time, sys
from urllib.parse import urlparse, parse_qsl, unquote
import socket
import re, base64, struct
import json
import threading

from functions import (CFG, addSlashes, is_online, send_tlss_command, recv_tlss_message, request_cgi, recv_timeout, list_device, dbconMaster, log, info_to_db )
from chkLic import  getMac

info_to_db('counting_main', change_log)

MYSQL = { 
    "commonParam": CFG('MYSQL', 'DB') + "." + CFG('DB_COMMON', 'PARAM'),
    "commonSnapshot": CFG('MYSQL', 'DB') + "." + CFG('DB_COMMON', 'SNAPSHOT'),
    "commonCounting": CFG('MYSQL', 'DB') + "." + CFG('DB_COMMON','COUNTING'),
    "commonHeatmap": CFG('MYSQL', 'DB') +"." + CFG('DB_COMMON', 'HEATMAP'),
    "commonCountEvent": CFG('MYSQL', 'DB') + "." + CFG('DB_COMMON', 'COUNT_EVENT'),
    "CustomCounting": CFG('DB_CUSTOM', 'COUNT'),
    "CustomCameraDB": CFG('DB_CUSTOM', 'CAMERA'),
    "CustomCounterlabelDB": CFG('DB_CUSTOM', 'COUNTER_LABEL'),
}

print (MYSQL)

def setScreen():
    label = []
    string = []

resetHour = "04:00"

def getDataByUid(uuid, sections=[]):
    arr = dict()
    dbconn0 = dbconMaster()
    
    db_name = "cnt_demo"
    with dbconn0:
        cur = dbconn0.cursor()
        for section in sections:
            if section == 'yesterday':
                arr[section] = dict()
                time_ref = time.mktime(time.strptime(time.strftime("%Y-%m-%d 00:00:00"), "%Y-%m-%d 00:00:00")) -3600*24 + 3600*8
                sq = "select  counter_label, sum(counter_val) from " + db_name + "." + MYSQL['CustomCounting'] + " where timestamp >= %d and timestamp <%d group by counter_label" %(time_ref,(time_ref+3600*24))
                print (sq)
                cur.execute(sq)
                rows = cur.fetchall()
                print(rows)
                for row in rows:
                    arr[section][row[0]] =  int(row[1])
            
            if section == 'today':
                arr[section] = dict()
                sq = "select  A.device_info, A.counter_name, A.counter_val, C.counter_label, A.timestamp, A.regdate from " + MYSQL['commonCountEvent'] + " as A inner join " + db_name + "." + MYSQL['CustomCameraDB'] +" as B inner join " + db_name +"." + MYSQL['CustomCounterlabelDB'] + " as C on A.device_info = B.device_info and B.code = C.camera_code and A.counter_name = C.counter_name   order by A.timestamp desc limit 1"
                print (sq)
                cur.execute(sq)
                rows = cur.fetchall()
                print(rows)
                # for row in rows:
                #     arr[section][row[0]] =  int(row[1])                    

    # print (arr)

def send_screen_data(conn, qsl_str):
    try:
        qsl_strb = qsl_str.encode('ascii')
    except UnicodeEncodeError as e:
        qsl_strb = qsl_str.encode('utf-8')

    try:
        conn.send(qsl_strb) # send byte type
    except TypeError as e:
        conn.send(str(e).encode('ascii')) # send byte type  

arr_screen = [
    'screen?label=menu&no=0&text=XXXX电子客流报表&font=[simhei,80,bold]&fg=white&bg=black&width=0&x=500&y=40',
    'screen?label=menu&no=1&text=今日进客流&font=[simhei,50,bold]&fg=white&bg=green&width=10&x=100&y=200',
    'screen?label=menu&no=2&text=&font=[simhei,50,bold]&fg=white&bg=purple&width=10&x=540&y=200',
    'screen?label=menu&no=3&text=限制人数&font=[simhei,50,bold]&fg=white&bg=red&width=10&x=980&y=200',
    'screen?label=menu&no=4&text=累计进人数&font=[simhei,50,bold]&fg=white&bg=green&width=10&x=1420&y=200',
    'screen?label=menu&no=5&text=营业时间&font=[simhei,50,bold]&fg=white&bg=blue&width=10&x=100&y=600',

    'screen?label=menu&no=7&text=服务电话&font=[simhei,50,bold]&fg=white&bg=blue&width=10&x=1420&y=600',

    'screen?label=number&no=1&text=3500&font=[ds-digital,60,bold]&fg=red&bg=black&width=10&x=56&y=300&anchor=e',
    'screen?label=number&no=2&text=3500&font=[ds-digital,60,bold]&fg=red&bg=black&width=10&x=490&y=300&anchor=e',
    'screen?label=number&no=3&text=3500&font=[ds-digital,60,bold]&fg=red&bg=black&width=10&x=980&y=300&anchor=e',
    'screen?label=number&no=4&text=115200&font=[ds-digital,60,bold]&fg=red&bg=black&width=10&x=1420&y=300&anchor=e',
    'screen?label=menu&no=6&text=&font=[ds-digital,60,bold]&fg=red&bg=black&width=16&x=600&y=600',

]


import random


def event_queryDB(conn):
    dbconn0 = dbconMaster()
    cur = dbconn0.cursor()
    uuid = ""

    while True:
        data = recv_timeout(conn)
        data = data.strip()
        print (data)
        if data == b'done':
            break

        data = data.decode('ascii')
        rs = urlparse(data)
        cmd = rs.path
        rs_t = dict(parse_qsl(rs.query))
        print(rs_t)
        if cmd == 'screen':
            uuid = rs_t['uuid']
            for qsl_str in arr_screen:
                send_screen_data(conn, qsl_str)
                time.sleep(0.5)

        if not data:
            if uuid:
                qsl_str = 'change?label=number&no=1&text=%d' %(random.randint(100,3000))
                send_screen_data(conn, qsl_str)
                time.sleep(1)

          
    print ("done")
    cur.close()
    dbconn0.close()
    conn.close()
    return True

class  ThQueryDB(threading.Thread):
    def __init__(self):
        threading.Thread.__init__(self)
        self.daemon= True
    
    def run(self):
        try:
            self.s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        except socket.error as  msg:
            log.critical("Could not create socket. Error Code: {0}, Error: {1}".format(str(msg[0], msg[1])))
            sys.exit(0)
        log.info("[-] Socket Created(query DB)")
        print("[-] Socket Created(query DB)")

        try:
            self.s.bind((CFG('SERVICE', 'HOST'), int(CFG('PORT', 'QUERY_DB'))))
            log.info("[-] Socket Bound to port {0}".format(str(CFG('PORT', 'QUERY_DB'))))
            print("[-] Socket Bound to port {0}".format(str(CFG('PORT', 'QUERY_DB'))))
        
        except socket.error as msg:
            log.critical("query DB, Bind Failed. Error: {0}".format(str(msg)))
            print ("query DB: Bind Failed. Error: {0}".format(str(msg)))
            self.s.close()
            sys.exit()

        self.s.listen(30) 
        print("QUERY_DB Engine: Listening...") 

        while True :
            self.conn, self.addr = self.s.accept()
            # log.info("COUNT_EVENT: %s:%s connected" %(self.addr[0], str(self.addr[1])))
            print ("QUERY_DB: %s:%s connected" %(self.addr[0], str(self.addr[1])))
            self.t0 = threading.Thread(target=event_queryDB, args=(self.conn, ))
            self.t0.start()

        self.s.close()        


if __name__ == '__main__':
    uuid= '0012232'
    # getDataByUid(uuid, sections=['yesterday','today'])
    print (CFG('SERVICE','COUNT_EVENT'))
    if CFG('SERVICE','COUNT_EVENT') == 'HTTP' or CFG('SERVICE','COUNT_EVENT') == 'TCP':
        tq = ThQueryDB()
        tq.start()         

    while True:
        print (tq, tq.is_alive())
        # print (te, te.is_alive())

        if (tq.is_alive() == False):
            sys.exit()
        time.sleep(30)          