change_log = """
###############################################################################
counting_main.py
2020-12-25, version 0.9, build 104 : query_countingreport, return if No Data
2020-12-26, service for device to common database, 
2021-01-27, added import 'info_to_db'
2021-02-17, only for python3, erase python2 code
2021-02-17, connecting pymysql -> with, executemany 
2021-04-09, In some PC  environment, (?,?~) cannot be used -> (%,%~)
2021-04-09, Function write_param(conn, device_info)
2021-05-04, program halt when network unstable, add try method

###############################################################################
"""
# print (change_log)

import os, time, sys
from http.client import HTTPConnection
from urllib.parse import urlparse, parse_qsl, unquote
import socket
import re, base64, struct
import threading

from functions import (CFG, addSlashes, is_online, send_tlss_command, recv_tlss_message, request_cgi, recv_timeout, list_device, dbconMaster, log, info_to_db, parseParam, parseCountReport, parseHeatmapData, parseEventData)

info_to_db('counting_main', change_log)

MYSQL = { 
    "commonParam": CFG('MYSQL', 'DB') + "." + CFG('DB_COMMON', 'PARAM'),
    "commonSnapshot": CFG('MYSQL', 'DB') + "." + CFG('DB_COMMON', 'SNAPSHOT'),
    "commonCounting": CFG('MYSQL', 'DB') + "." + CFG('DB_COMMON','COUNTING'),
    "commonHeatmap": CFG('MYSQL', 'DB') +"." + CFG('DB_COMMON', 'HEATMAP'),
    "commonCountEvent": CFG('MYSQL', 'DB') + "." + CFG('DB_COMMON', 'COUNT_EVENT')
}

def write_param(conn, device_info=''):
    regdate = time.strftime("%Y-%m-%d %H:%M:%S")
    print ("write cgi commands to %s at %s" %(device_info, regdate))
    dbconn0 = dbconMaster()
    with dbconn0:
        cur = dbconn0.cursor()    
        sq = "select write_cgi_cmd from " +  MYSQL['commonParam'] + " where device_info = '%s' and write_cgi_cmd is not null and write_cgi_cmd != '' limit 1 " %device_info
        cur.execute(sq)
        row = cur.fetchone()
    
        if row:
            arr_cgi_cmd = row[0].splitlines()
            for cgi_cmd in arr_cgi_cmd:
                    if not cgi_cmd.strip():
                            continue
                    print(cgi_cmd)
                    rs = request_cgi(conn, cgi_cmd, encoding='byte')
                    if not rs:
                        return False
                    spos = rs.find(b"\n\r")
                    body = rs[spos:].strip()
                    log.info("send command %s: %s, reply: %s" %(device_info, cgi_cmd, body.decode('utf-8').replace("\n","<br>")))

            sq = "update " + MYSQL['commonParam'] + " set write_cgi_cmd='' where device_info = '%s' " %device_info
            cur.execute(sq)
            dbconn0.commit()

    cur.close()
    return True

def query_param(conn, device_info='') :
    regdate = time.strftime("%Y-%m-%d %H:%M:%S")
    print ("getting param info from %s at %s" %(device_info, regdate))
    cgi_cmd_str = "/uapi-cgi/param.fcgi?action=list"
    rs = request_cgi(conn, cgi_cmd_str, encoding='byte')
    if not rs:
        return False
    if rs.find(b"404 - Not Found") >=0  or rs.find(b"#400|ILLEGAL SYNTAX") >=0: # IPE, NVC
        cgi_str = "/nvc-cgi/admin/param.fcgi?action=list"
        rs1 = request_cgi(conn, cgi_str, encoding='byte' )
        rs1 = rs1.replace(b"Brand.prodshortname", b"BRAND.Product.shortname")

        cgi_str = "/nvc-cgi/admin/vca.cgi?action=list"
        rs2 = request_cgi(conn, cgi_str, encoding='byte' )
        rs2 = rs2[0: rs2.index(b'XML.Meta.version=2.0')]
        rs = rs1 + b'\n' + rs2

    spos = rs.index(b"\n\r") if CFG('SERVICE','MODE')=='TLSS' else 0
    body = rs[spos:].strip()
    param = parseParam(body)
    if not (param['brand'] and  param['model'] and param['mac']) :
        try :
            tt = rs[:600].decode('utf-8').replace("\n","<br>")
            tt = '<br>' + tt
        except:
            tt = "decode error"
        log.error("Retrieve Param faild: %s" %tt)
        # print (param)
        return False

    if not device_info:
        device_info = "mac=%s&brand=%s&model=%s" %(param['mac'], param['brand'], param['model'])
    
    dbconn0 = dbconMaster()
    with dbconn0:
        cur = dbconn0.cursor()
        sq = "select pk from " + MYSQL['commonParam'] + " where device_info = %s limit 1" 
        cur.execute(sq, device_info)
        row = cur.fetchone()
        if not row :
            sq = "insert into " + MYSQL['commonParam'] + "(device_info, initial_access, usn, product_id) values(%s, %s, %s, %s)"
            cur.execute(sq, (device_info, regdate, param['usn'], param['productid']))
            dbconn0.commit()
		
        sq = "update " + MYSQL['commonParam'] + " set product_id=%s, lic_pro=%s, lic_surv=%s, lic_count=%s, heatmap=%s, countrpt=%s, face_det=%s, macsniff=%s, param=%s, last_access=%s, url=%s where device_info=%s" 
        
        cur.execute(sq, (param['productid'], param['lic_pro'], param['lic_surv'], param['lic_count'], param['heatmap'], param['countrpt'], param['face_det'], param['macsniff'], param['param'], regdate, param['url'], device_info))
        dbconn0.commit()
        log.info ("%s update param OK" %device_info)
        print ("update params from %s at %s" %(device_info, regdate))
 
    return param

def querySnapshot(conn, device_info):
    regdate = time.strftime("%Y-%m-%d %H:%M:%S")
    print ("getting snapshot from %s at %s" %(device_info, regdate))

    cgi_str = "/nvc-cgi/operator/snapshot.fcgi"
    rs = request_cgi(conn, cgi_str, encoding='byte')
    if not rs:
        return False
    spos = rs.index(b"\n\r") if CFG('SERVICE','MODE')=='TLSS' else 0
    body = rs[spos:].lstrip()
    snapshot = b'data:image/jpg;base64,' + base64.b64encode(body)
    snapshot = addSlashes(snapshot.decode('utf-8'))
    
    record = [snapshot, regdate]
    dbconn0 = dbconMaster()
    with dbconn0:
        cur = dbconn0.cursor()
        sq = "select pk from  " + MYSQL['commonSnapshot'] + " where device_info = '%s' order by pk desc limit 1" %(device_info)
        cur.execute(sq)
        row = cur.fetchone()

        if row:
            sq = "update " + MYSQL['commonSnapshot'] + " set body=%s, regdate=%s where pk=%s " 
            record.append(row[0])

        else:
            sq = "insert into " + MYSQL['commonSnapshot'] + "(body, regdate, device_info) values(%s, %s, %s)" 
            record.append(device_info)
        record = tuple(record)
        cur.execute(sq, record)
        dbconn0.commit()
    log.info("%s: snapshot updated" %device_info)
    return True


def query_countingreport(conn, device_info) :
    regdate = time.strftime("%Y-%m-%d %H:%M:%S")
    print ("getting counting reports from %s at %s" %(device_info, regdate))

    dbconn0 = dbconMaster()
    with dbconn0:
        cur = dbconn0.cursor()
        sq = "select timestamp from " + MYSQL['commonCounting'] + " where device_info='%s' order by timestamp desc limit 1" %device_info
        cur.execute(sq)
        row = cur.fetchone()
        
        if row :
            from_t = time.strftime("&from=%Y/%m/%d%%20%H:%M", time.gmtime(row[0]))
            readflag = int(time.time())+ 8*3600 - int(row[0])

        else :
            from_t = "&from=2018/11/12%2000:00"
            readflag = 1300
    
    if (readflag <=700):
        print ("%s: no more record" %device_info) 
        return False

    cgi_str = "/cgi-bin/operator/countreport.cgi?reportfmt=csv&to=now-600&counter=active&sampling=600&order=Ascending&value=diff%s" %from_t
    rs = request_cgi(conn, cgi_str, encoding='byte') 
    # print (rs)
    if not rs:
        return False
    if rs.find(b"fail data load") >=0 :
        return False

    spos = rs.index(b"\n\r") if CFG('SERVICE','MODE')=='TLSS' else 0
    body = rs[spos:].strip()
    
    arr_record = parseCountReport(body)
    # [{'datetime': '2021/02/23 10:49:00', 'timestamp': 1614077340, 'ct_id': 0, 'ct_name': 'Counter 0', 'ct_value': 3933015},  ]
    if arr_record == False: # return fail on error with parseCountReport
        log.error("Fail on error with parseCountReport: %s" %device_info)
        return False

    dbconn0 = dbconMaster()
    with dbconn0:
        cur = dbconn0.cursor()
        for record_dict in arr_record:
            sq = "select pk from " + MYSQL['commonCounting'] + " where timestamp < %d and flag='y' order by timestamp asc limit 1" %(int(time.time()) - int(CFG('MYSQL', 'RECYCLING_TIMESTAMP')))

            cur.execute(sq)
            rowa = cur.fetchone()
            
            record = [device_info, regdate, record_dict['timestamp'], record_dict['datetime'], record_dict['ct_name'], record_dict['ct_value']]

            if rowa:
                record.append(rowa[0])
                sq = "update " + MYSQL['commonCounting'] + " set device_info= %s, regdate=%s, timestamp = %s, datetime= %s, counter_name= %s, counter_val= %s, flag='n' where pk = %s" 

            else:
                sq = "insert into " + MYSQL['commonCounting'] + "(device_info, regdate, timestamp, datetime, counter_name, counter_val) values(%s, %s, %s , %s, %s, %s)" 

            # print (sq, record)
            cur.execute(sq, tuple(record))
            log.info( "{0}:{1}:{2}:{3} updated".format(device_info, MYSQL['commonCounting'], record[3], record[4]))
		
        dbconn0.commit()
    return True

def query_heatmap(conn,  device_info) :
    regdate = time.strftime("%Y-%m-%d %H:%M:%S")
    print ("getting heatmap reports from %s at %s" %(device_info, regdate))

    dbconn0 = dbconMaster()
    with dbconn0:
        cur = dbconn0.cursor()
        sq = "select timestamp from " + MYSQL['commonHeatmap'] + " where device_info='%s' order by timestamp desc limit 1" %device_info
        #	print (sq)
        cur.execute(sq)
        row = cur.fetchone()
        if row :
            from_t = time.strftime("&from=%Y/%m/%d%%20%H:%M", time.gmtime(row[0]+3600))
            readflag = int(time.time()) + 8*3600 - int(row[0])
        else :
            from_t = "&from=2018/11/12%2000:00"
            readflag = 3700

    if (readflag <= 3600) :	
        return False

    cgi_cmd_str = "/uapi-cgi/reporthm.cgi?reportfmt=csv&to=now&table=3&individual=yes%s" %from_t
    rs = request_cgi(conn, cgi_cmd_str, encoding="byte")
    if not rs:
        return False
    spos = rs.index(b"\n\r") if CFG('SERVICE','MODE')=='TLSS' else 0
    body = rs[spos:].strip()

    arr_record = []
    arr_rs = parseHeatmapData(body)
    if arr_record == False: # return fail on error with parseHeatmapData
        log.error("Fail on error with parseHeatmapData: %s" %device_info)
        return False

    for rs in arr_rs:
        arr_record.append([device_info, regdate, rs['timestamp'], rs['datetime'], rs['heatmap']])

    dbconn0 = dbconMaster()
    with dbconn0:      
        cur = dbconn0.cursor()
        for record in arr_record:
            sq = "select pk from " + MYSQL['commonHeatmap'] + " where timestamp < %d and flag = 'y' order by timestamp asc limit 1 " %(int(time.time()) - int(CFG('MYSQL', 'RECYCLING_TIMESTAMP'))) 
            cur.execute(sq)
            rowa = cur.fetchone()
            if rowa:
                record.append(rowa[0])
                sq = "update " + MYSQL['commonHeatmap'] + " set device_info=%s, regdate=%s, timestamp=%s, datetime=%s, body_csv=%s, flag='n' where pk =%s"
            else :
                sq = "insert into " + MYSQL['commonHeatmap'] + "(device_info, regdate, timestamp, datetime, body_csv)  values(%s, %s, %s, %s, %s)"
        	
            record = tuple(record)
            # print (sq, record)
            cur.execute(sq, record)
            log.info( "{0}:{1}:{2} updated".format(device_info, MYSQL['commonHeatmap'], record[3]))

        dbconn0.commit()
    return True

####################################### ACTIVE COUNTING #################################################

def deviceinfoToDB():
    regdate = time.strftime("%Y-%m-%d %H:%M:%S")
    print ("browsing devices at %s" %(regdate))

    arr_dev = list_device()
    arr_record=[]
    for dev in arr_dev:
        if not (dev['mac'] and dev['brand'] and dev['model']) :
            log.warning ("device_info Error mac:%s, brand:%s, model:%s " %(dev['mac'], dev['brand'], dev['model']))
            print ("device_info Error mac:%s, brand:%s, model:%s " %(dev['mac'], dev['brand'], dev['model']))
            continue
        device_info = "mac=%s&brand=%s&model=%s" %(dev['mac'], dev['brand'], dev['model'])
        regdate = time.strftime("%Y-%m-%d %H:%M:%S")
        arr_record.append([device_info, dev['usn'], dev['location'], regdate])

    dbconn0 = dbconMaster()
    with dbconn0:
        cur = dbconn0.cursor()
        for record in arr_record:
            sq = "select pk from " + MYSQL['commonParam'] + " where device_info=%s" 
            cur.execute(sq, record[0])
            rowa = cur.fetchone()
            sq = "select pk from " + MYSQL['commonSnapshot'] + " where device_info=%s"
            cur.execute(sq, record[0])
            rowb = cur.fetchone()

            if not rowa :
                sq = "insert into " + MYSQL['commonParam'] + "(device_info, usn, url, initial_access) values(%s, %s, %s, %s)" 
                record = tuple(record)
                cur.execute(sq, record)
            
            if not rowb:
                sq = "insert into " + MYSQL['commonSnapshot'] + "(device_info, regdate) values(%s, %s)"
                cur.execute(sq, (record[0], record[3]))  
        dbconn0.commit()
    print ("browsing device and info to db succeed")

def getDataFromDevice():
    regdate = time.strftime("%Y-%m-%d %H:%M:%S")
    print ("Getting parameters, snapshot, count, heatmap at %s" %(regdate))
    log.info("Getting parameters, snapshot, count, heatmap")

    arr_dev = []
    nums_online = 0
    dbconn0 = dbconMaster()
    with dbconn0:
        cur = dbconn0.cursor()
        sq = "select pk, device_info, url from " + MYSQL['commonParam'] + " order by last_access desc limit 100"
        # print (self.sq)
        cur.execute(sq)
        rows = cur.fetchall()
        
        for row in rows:
            arr_dev.append(row)
    
    for dev in arr_dev:
        pk, device_info, dev_ip = dev
        if not is_online(dev_ip):
            log.warning ("device %s: %s is not online!!" %(dev_ip, device_info))
            continue

        server = (dev_ip, 80)
        conn = HTTPConnection(*server)
        try:
            write_param(conn, device_info)
        except Exception as e:
            print (e)
            print ("fail to write params")
            pass
        try:
            param = query_param(conn)
        except Exception as e:
            print (e)
            print ("fail to get params")
            param = None
        try:
            querySnapshot(conn, device_info)
        except Exception as e:
            print (e)
            print ("fail to get snapshot")
            pass


        if param : 
            if param['countrpt'] == 'y':
                try:
                    rs = query_countingreport(conn, device_info)
                except Exception as e:
                    print (e)
                    print ("fail to get counting reports")
                    pass

            if param['heatmap'] == 'y':
                try:
                    rs = query_heatmap(conn, device_info)
                except Exception as e:
                    print (e)
                    print ("fail to get heatmap")
                    pass
    
        nums_online += 1
        conn.close()
    return nums_online


def setDatetimeToDevice():
    arr_dev = []
    nums_online = 0
    dbconn0 = dbconMaster()    

    with dbconn0:
        cur = dbconn0.cursor()
        sq = "select device_info, url from " + MYSQL['commonParam'] + " order by last_access desc limit 100"
        cur.execute(sq)
        rows = cur.fetchall()
        for row in rows:
            arr_dev.append(row)        

    param = dict()
    arr_cgi_read = [
        ("SYSTEM.Datetime.syncsource", "syncsource"),
        ("SYSTEM.Datetime.Date.format", "dateformat"),
        ("SYSTEM.Datetime.Time.format", "timeformat"),
        ("SYSTEM.Datetime.Tz.name", "timezone"),
        ("SYSTEM.Datetime.Tz.posixrule", "posixrule"),
        ("SYSTEM.Datetime.Tz.utcoffset", "utcoffset"),
    ]

    for dev in arr_dev:
        device_info, dev_ip = dev
        if not is_online(dev_ip):
            log.warning ("device %s is not online!!" %dev_ip)
            continue

        server = (dev_ip, 80)
        conn = HTTPConnection(*server)
        cgi_str = "/uapi-cgi/param.fcgi?action=list&group=SYSTEM.Datetime"
        rs = request_cgi(conn, cgi_str)
        if not rs:
            continue
        if rs.find("404 - Not Found") >=0:
            continue

        # print(rs)
        lines  = rs.splitlines()
        for line in lines:
            key, val = line.split("=")
            for p_str in arr_cgi_read:
                if key == p_str[0]:
                    param[p_str[1]] = val
                    break

        # print(param)
        if param['timezone'] != "Hong_Kong":
            cgi_str = "/uapi-cgi/param.fcgi?action=update&group=SYSTEM.Datetime.Tz&name=Hong_Kong"
            rs = request_cgi(conn, cgi_str)
            cgi_str = "/uapi-cgi/param.fcgi?action=update&group=SYSTEM.Datetime.Tz&posixrule=HKT-8"
            rs = request_cgi(conn, cgi_str)
            cgi_str = "/nvc-cgi/admin/timezone.cgi?action=set"
            rs = request_cgi(conn, cgi_str)
            log.info("%s: Setting Timezone Hong Kong" %device_info)

        cgi_str = "/nvc-cgi/admin/param.fcgi?action=update&group=System.DateTime&datetime=%s" %(time.strftime("%m%d%H%M%Y.%S"))
        rs = request_cgi(conn, cgi_str)
        log.info("%s: Setting datetimezone OK" %device_info)
        cgi_str = "nvc-cgi/admin/param.fcgi?action=list&group=System.DateTime.datetime"
        rs = request_cgi(conn, cgi_str)
        print (rs)
        conn.close()
        nums_online += 1
    
    return nums_online

class ThActiveCounting(threading.Thread):
    def __init__(self):
        threading.Thread.__init__(self)
        self.daemon = True
        self.TimeSetFlag = int(time.strftime("%m%d")) -1
        self.Running = True
        self.i = 0
        
    def run(self):
        while self.Running:
            ts = time.time()
            log.info ("======== Starting, %d ========" %self.i)
            print ("Active Counting, starting %d" %self.i)
            deviceinfoToDB()        
        
            if self.TimeSetFlag < int(time.strftime("%m%d")) and int(time.strftime("%H")) < 2: # Am 0:00 ~ 02:00, once
                log.info("Setting device time")
                setDatetimeToDevice()
                self.TimeSetFlag = int(time.strftime("%m%d"))
                self.i = 0
            
            num_online = getDataFromDevice()
            
            te = time.time()
            dtime = 300 - int(te - ts) 
            if dtime < 0:
                dtime = 1

            str_s = "Online %d, elaspe time: %d, need %d sec sleep" %(num_online, (te-ts), dtime)
            print (str_s)
            log.info("ThActiveCounting: " + str_s)

            # if not num_online:
            #     self.Running = False
            time.sleep(dtime)
            self.i += 1

            
############################  TLSS  #################################################################            

def tlss_client_thread(conn):
    device_info_b = recv_tlss_message(conn)
    print (device_info_b)
    try:
        device_info = device_info_b.decode('ascii')
    except :
        log.error("Invalid device info: {0}".format(device_info))
        print ("Invalid device Info")
        conn.close()
        return False

    tabs = device_info.split("&")
    if (len(tabs) != 3) or  (tabs[0].find("mac=") < 0) or (tabs[1].find("brand=") < 0) or (tabs[2].find("model=") < 0)  :
        log.error("Invalid device info: {0}".format(device_info))
        conn.close()
        return False

    start_timestamp = time.time()
    regdate = time.strftime("%Y-%m-%d %H:%M:%S")
    log.info("{0} connected tlss: {1}".format(device_info, regdate))


    write_param(conn, device_info)
    query_param(conn, device_info)
    querySnapshot(conn, device_info)

    dbconn0 = dbconMaster()
    with dbconn0:
        cur = dbconn0.cursor()
        sq = "select pk, db_name, heatmap, countrpt from " + MYSQL['commonParam'] + " where device_info=%s" 
        # print sq
        cur.execute(sq, device_info)
        row = cur.fetchone()
        pk, db_name, heatmap, countrpt =  row

    if db_name == 'none':
        log.info("%s, elaspe time : %d sec" %(device_info,  int(time.time()-start_timestamp)))
        conn.close()
        return False

    if countrpt == 'y':
        query_countingreport(conn, device_info)

    if heatmap == 'y' :
        query_heatmap(conn, device_info)

    send_tlss_command(conn, "done\0")
    log.info("%s, elaspe time : %d sec" %(device_info,  int(time.time()-start_timestamp)))

    conn.close()

class ThTLSS(threading.Thread):
    def __init__(self):
        threading.Thread.__init__(self)
        self.daemon = True
        self.i = 0
        
    def run(self):
        try:
            self.s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        except socket.error as  msg:
            log.critical("Could not create socket. Error Code: {0}, Error: {1}".format(str(msg[0], msg[1])))
            sys.exit(0)
        log.info("[-] Socket Created(TLSS)")

        try:
            self.s.bind((CFG('SERVICE', 'HOST'), int(CFG('PORT', 'TLSS'))))
            log.info("[-] Socket Bound to port {0}".format(str(CFG('PORT', 'TLSS'))))
        
        except socket.error as msg:
            log.critical("TLSS, Bind Failed. Error: {0}".format(str(msg)))
            print ("TLSS: Bind Failed. Error: {0}".format(str(msg)))
            self.s.close()
            sys.exit()

        self.s.listen(30) 
        print("TLSS Engine: Listening...") 

        while True :
            self.conn, self.addr = self.s.accept()
            # log.info("TLSS: %s:%s connected, %d" %(self.addr[0], str(self.addr[1]), self.i))
            print ("TLSS: %s:%s connected, %d" %(self.addr[0], str(self.addr[1]), self.i))
            self.t0 = threading.Thread(target=tlss_client_thread, args=(self.conn, ))
            self.t0.start()

            self.i += 1        
        self.s.close()


########################## COUNT EVENT ####################################################

def event_counting_thread(conn):
    data = recv_timeout(conn) #<class 'byte'>
    conn.close()
    arr_rs = parseEventData(data, CFG('SERVICE','COUNT_EVENT'))
    if not arr_rs:
        return False

    dbconn0 = dbconMaster()
    with dbconn0:
        cur = dbconn0.cursor()
        sq = "select device_info from " + MYSQL['commonParam'] + " where url=%s "
        cur.execute(sq, arr_rs[0]['ip'])
        row = cur.fetchone()
        deviceinfo = row[0]

        for rs in arr_rs:
            record = [rs['ip'], deviceinfo, time.strftime("%Y-%m-%d %H:%M:%S"), rs['timestamp'], rs['ct_name'], rs['ct_val'], addSlashes(rs['message'])]
            sq = "select pk from " + MYSQL['commonCountEvent'] + "  where timestamp < %s order by timestamp asc limit 1 "
            cur.execute(sq, (int(time.time()) - 3600*24) )
            row = cur.fetchone()
            if row:
                record.append(row[0])
                sq = "update " + MYSQL['commonCountEvent'] + " set device_ip=%s, device_info=%s, regdate=%s, timestamp=%s, counter_name=%s, counter_val=%s, message=%s, flag='y', status=0 where pk = %s"
            else:
                sq = "insert into " + MYSQL['commonCountEvent'] + "(device_ip, device_info, regdate, timestamp, counter_name, counter_val, message, flag, status)  values(%s, %s, %s, %s, %s, %s, %s, 'y', 0) "
            # print (sq, tuple(record))
            cur.execute(sq, tuple(record))
            log.info("count Event %s, %s, %s," %(tuple(record)))
        dbconn0.commit()



class  ThEventCounting(threading.Thread):
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
            self.s.bind((CFG('SERVICE', 'HOST'), int(CFG('PORT', 'COUNT_EVENT'))))
            log.info("[-] Socket Bound to port {0}".format(str(CFG('PORT', 'COUNT_EVENT'))))
        
        except socket.error as msg:
            log.critical("EventCounting, Bind Failed. Error: {0}".format(str(msg)))
            print ("EventCounting: Bind Failed. Error: {0}".format(str(msg)))
            self.s.close()
            sys.exit()

        self.s.listen(30) 
        print("COUNT_EVENT Engine: Listening...") 

        while True :
            self.conn, self.addr = self.s.accept()
            # log.info("COUNT_EVENT: %s:%s connected" %(self.addr[0], str(self.addr[1])))
            print ("COUNT_EVENT: %s:%s connected" %(self.addr[0], str(self.addr[1])))
            self.t0 = threading.Thread(target=event_counting_thread, args=(self.conn, ))
            self.t0.start()

        self.s.close()        


if __name__ == '__main__':
    
    if CFG('SERVICE','MODE') == 'TLSS':
        # TLSS()
        tc = ThTLSS()
        tc.start()        
    elif CFG('SERVICE','MODE') == 'ACTIVE':
        # thActive()
        tc = ThActiveCounting()
        tc.start()

    if CFG('SERVICE','COUNT_EVENT') == 'HTTP' or CFG('SERVICE','COUNT_EVENT') == 'TCP':
        # thActive()
        te = ThEventCounting()
        te.start() 

    while True:
        print (tc, tc.is_alive())
        # print (te, te.is_alive())

        if (tc.is_alive() == False):
            print ("restarting Thread")
            if CFG('SERVICE','MODE') == 'TLSS':
                # TLSS()
                tc = ThTLSS()
                tc.start()        
            elif CFG('SERVICE','MODE') == 'ACTIVE':
                # thActive()
                tc = ThActiveCounting()
                tc.start()
            # sys.exit()
            
        time.sleep(30)  
        

