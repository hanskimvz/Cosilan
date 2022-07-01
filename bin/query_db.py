import sys, os, time
import socket
import json
import random
import threading

from functions_s import (dbconMaster, CFG, log)

# need to edit
# MYSQL = { 
#     "commonParam": CFG('MYSQL', 'DB') + "." + CFG('DB_COMMON', 'PARAM'),
#     "commonSnapshot": CFG('MYSQL', 'DB') + "." + CFG('DB_COMMON', 'SNAPSHOT'),
#     "commonCounting": CFG('MYSQL', 'DB') + "." + CFG('DB_COMMON','COUNTING'),
#     "commonHeatmap": CFG('MYSQL', 'DB') +"." + CFG('DB_COMMON', 'HEATMAP'),
#     "commonCountEvent": CFG('MYSQL', 'DB') + "." + CFG('DB_COMMON', 'COUNT_EVENT')
# }

def genData():
    arr_rs = []
    for i in range(86400):
        arr_rs.append(random.randint(1000,9999))
    return arr_rs
def query_db_thread(conn):
    for i in range(100):
        # data = recv_timeout(conn, timeout=1) #<class 'byte'>
        arr_rs = []
        data = conn.recv(1024)
        if data == b"done" :
            conn.close()
            return False
        print (data)
        data = data.decode('utf-8')
        if data.find("sql:") >=0:
            try:
                sq = data[data.index('sql:')+ len('sql:'):]
                print (sq)
                dbconn0 = dbconMaster()
                with dbconn0.cursor() as cur:
                    # sq = "select device_info from " + MYSQL['commonParam'] + " where url=%s "
                    cur.execute(sq)
                    rows = cur.fetchall()
                    for row in rows:
                        arr_rs.append(row)

                data_s =  json.dumps(arr_rs)
            except:
                data_s = ""
        elif data.find("getdata:") >=0:
            try:
                data_s = json.dumps(genData())
            except:
                data_s = "fail to gendata"
            conn.send(data_s.encode('utf-8'))                   

    # if not arr_rs:
    #     return False

    # dbconn0 = dbconMaster()
    # with dbconn0:
    #     cur = dbconn0.cursor()
    #     sq = "select device_info from " + MYSQL['commonParam'] + " where url=%s "
    #     cur.execute(sq, arr_rs[0]['ip'])
    #     row = cur.fetchone()
    #     deviceinfo = row[0]

    #     for rs in arr_rs:
    #         record = [rs['ip'], deviceinfo, time.strftime("%Y-%m-%d %H:%M:%S"), rs['timestamp'], rs['ct_name'], rs['ct_val'], addSlashes(rs['message'])]
    #         sq = "select pk from " + MYSQL['commonCountEvent'] + "  where timestamp < %s order by timestamp asc limit 1 "
    #         cur.execute(sq, (int(time.time()) - 3600*24) )
    #         row = cur.fetchone()
    #         if row:
    #             record.append(row[0])
    #             sq = "update " + MYSQL['commonCountEvent'] + " set device_ip=%s, device_info=%s, regdate=%s, timestamp=%s, counter_name=%s, counter_val=%s, message=%s, flag='y', status=0 where pk = %s"
    #         else:
    #             sq = "insert into " + MYSQL['commonCountEvent'] + "(device_ip, device_info, regdate, timestamp, counter_name, counter_val, message, flag, status)  values(%s, %s, %s, %s, %s, %s, %s, 'y', 0) "
    #         # print (sq, tuple(record))
    #         cur.execute(sq, tuple(record))
    #     dbconn0.commit()

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
        log.info("[-] Socket Created(COUNT_EVENT)")

        try:
            self.s.bind((CFG('SERVICE', 'HOST'), int(CFG('PORT', 'QUERY_DB'))))
            log.info("[-] Socket Bound to port {0}".format(str(CFG('PORT', 'QUERY_DB'))))
        
        except socket.error as msg:
            log.critical("QUERY_DB, Bind Failed. Error: {0}".format(str(msg)))
            print ("QUERY_DB: Bind Failed. Error: {0}".format(str(msg)))
            self.s.close()
            sys.exit()

        self.s.listen(30) 
        print("QUERY_DB Engine: Listening...") 

        while True :
            self.conn, self.addr = self.s.accept()
            # log.info("COUNT_EVENT: %s:%s connected" %(self.addr[0], str(self.addr[1])))
            print ("QUERY_DB: %s:%s connected" %(self.addr[0], str(self.addr[1])))
            self.t0 = threading.Thread(target=query_db_thread, args=(self.conn, ))
            self.t0.start()

        self.s.close()       


if __name__ == '__main__':
    th = ThQueryDB()
    th.start()
    while True:
        print (th, th.is_alive())
        if th.is_alive() == False:
            th = ThQueryDB()
            th.start()
          
        time.sleep(30)