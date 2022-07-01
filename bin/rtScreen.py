change_log = """
###############################################################################
rtCount.py
2021-04-16, version 0.9, first

###############################################################################
"""
import time, datetime, os, sys,random
import socket
import json
import pymysql
from tkinter import *
import threading

import uuid


cwd = os.path.abspath(os.path.dirname(sys.argv[0]))
os.chdir(cwd)


MYSQL =dict()
TZ_OFFSET = 3600*8


def getMac():
	mac = "%012X" %(uuid.getnode())
	return mac

def dbconMaster(host='', user='', password='',  charset = 'utf8', port=0): #Mysql
    global MYSQL
    if not host:
        host=MYSQL['host']
    if not user :
        user = MYSQL['user']
    if not password:
        password = MYSQL['password']
    if not port:
        port = MYSQL['port']

    try:
        dbcon = pymysql.connect(host=host, user=str(user), password=str(password),  charset=charset, port=port)
    except pymysql.err.OperationalError as e :
        print (str(e))
        return None
    return dbcon   

def forgetLabel(label):
    global menus
    menus[label].place_forget()

def datetime_string():
    global timeshow_label
    if timeshow_label:
        dow = ["星期日","星期一","星期二","星期三","星期四","星期五","星期六"]
        w = dow[int(time.strftime("%w"))]
        text = time.strftime("%Y-%m-%d\n%H:%M:%S") + " " + w
        timeshow_label.config(text=text)
        timeshow_label.after(200, datetime_string)

def dateTss(tss):
    # tm_year=2021, tm_mon=3, tm_mday=22, tm_hour=21, tm_min=0, tm_sec=0, tm_wday=0, tm_yday=81, tm_isdst=-1
    year = int(tss.tm_year)
    month = int(tss.tm_mon) 
    day = int(tss.tm_mday)
    hour = int(tss.tm_hour)
    min = int(tss.tm_min)
    wday = int((tss.tm_wday+1)%7)
    week = int(time.strftime("%U", tss))
    
def getSquare(dbconn):
    cur = dbconn.cursor(pymysql.cursors.DictCursor)
    sq = "select * from %s.square " %(MYSQL['db'])
    cur.execute(sq)
    return cur.fetchall()

def getStore(dbconn):
    cur = dbconn.cursor(pymysql.cursors.DictCursor)
    sq = "select * from %s.store " %(MYSQL['db'])
    cur.execute(sq)
    return cur.fetchall()

def getCamera(dbconn):
    cur = dbconn.cursor(pymysql.cursors.DictCursor)
    sq = "select * from %s.camera " %(MYSQL['db'])
    cur.execute(sq)
    return cur.fetchall()

def getCounterLabel(dbconn):
    cur = dbconn.cursor(pymysql.cursors.DictCursor)
    sq = "select * from %s.counter_label " %(MYSQL['db'])
    cur.execute(sq)
    return cur.fetchall()

def getDevices(dbconn, device_info=''):
    cur = dbconn.cursor(pymysql.cursors.DictCursor)
    sq = "select pk, device_info, usn, product_id, lic_pro, lic_surv, lic_count, face_det, heatmap, countrpt, macsniff, write_cgi_cmd, initial_access, last_access, db_name, url, method, user_id, user_pw from common.params "
    if device_info:
        sq += " where device_info='%s'" %device_info
    else :
        sq += " where db_name='%s'" %(MYSQL['db'])
    cur.execute(sq)
    return cur.fetchall()

def getSnapshot(dbconn, device_info):
    cur = dbconn.cursor(pymysql.cursors.DictCursor)
    sq = "select body from common.snapshot where device_info='%s'" %(device_info)
    cur.execute(sq)
    return cur.fetchone()['body']


def getRptCounting(dbconn, day_before=0):
    global ref_start_timestamp, ref_end_timestamp
    arr = dict()
    ts_midnight = int((time.time() + TZ_OFFSET) //(3600*24)) * 3600*24

    start_ts = ts_midnight + ref_start_timestamp - 3600*24*day_before
    end_ts = ts_midnight + ref_end_timestamp - 3600*24*day_before

    if start_ts > int(time.time() + TZ_OFFSET):
        start_ts -= 3600*24
        end_ts -= 3600*24

    print(time.strftime("%Y-%m-%d %H:%M:%S", time.gmtime(start_ts)), " ~ ", time.strftime("%Y-%m-%d %H:%M:%S", time.gmtime(end_ts)))

    cur = dbconn.cursor(pymysql.cursors.DictCursor)
    sq = "select counter_label, sum(counter_val) as sum, max(timestamp) as latest_ts from %s.count_tenmin where timestamp >= %d and timestamp < %d group by counter_label" %(MYSQL['db'], start_ts, end_ts)
    cur.execute(sq)
    for assoc in cur.fetchall():
        arr[assoc['counter_label']]  = {
            'count' : int(assoc['sum']),
            'latest': assoc['latest_ts']
        }
    print(arr)
    print()
    return arr


def getRtCounting(dbconn, time_ref):
    # 1653298800	2022-05-23 09:40:00	Counter 0
    # 1653299308	2022-05-23 09:48:40Counter 1
    arr = {
        "entrance": {"start":0, "end":0, "count":0},
        "exit": {"start":0, "end": 0, "count":0}
    }
    arr_t = []
    
    cur = dbconn.cursor(pymysql.cursors.DictCursor)
    # sq = "select A.regdate, A.device_info, A.timestamp, A.counter_val as ct_val, B.code, C.counter_label as ct_label, C.counter_name as ct_name from common.counting_event  as A inner join %s.camera as B inner join %s.counter_label as C on A.device_info=B.device_info and B.code = C.camera_code and A.counter_name=C.counter_name where A.timestamp > %d and (C.counter_label = 'entrance' or C.counter_label='exit') order by timestamp asc" %(MYSQL['db'], MYSQL['db'], time_ref+3600*8) 
    sq = "select A.counter_val as ct_val, B.code, C.counter_label as ct_label, C.counter_name as ct_name from common.counting_event  as A inner join %s.camera as B inner join %s.counter_label as C on A.device_info=B.device_info and B.code = C.camera_code and A.counter_name=C.counter_name where A.timestamp > %d and (C.counter_label = 'entrance' or C.counter_label='exit') order by timestamp asc" %(MYSQL['db'], MYSQL['db'], time_ref) 

    print (sq)
    cur.execute(sq)
    for assoc in cur.fetchall():
        arr_t.append(assoc)
  
    # print(arr_t[:3])
    arr_f = []
    for assoc in arr_t:
        label = "%s%s%s" %(assoc['code'], assoc['ct_label'], assoc['ct_name'])
        if not (label in arr_f) :
            arr_f.append(label)
            arr[assoc['ct_label']]["start"] += assoc['ct_val']
    arr_t.reverse()
    # print(arr_t[:3])
    arr_f = []
    for assoc in arr_t:
        label = "%s%s%s" %(assoc['code'], assoc['ct_label'], assoc['ct_name'])
        if not (label in arr_f) :
            arr_f.append(label)
            arr[assoc['ct_label']]["end"] += assoc['ct_val']

    arr['entrance']['count'] = int(arr['entrance']["end"]-arr['entrance']["start"])
    arr['exit']['count'] = int(arr['exit']["end"] - arr['exit']["start"])
    # print(arr)
    return arr

def getRtCountingX(dbconn, arr_count):
    arr = dict()
    arr_t = []

    cur = dbconn.cursor(pymysql.cursors.DictCursor)
    print(arr_count)
    for ct_label in arr_count:
        arr[ct_label]= {
            "start":"",
            "abs_val_s": 0,
            "abs_val_e": 0,
            "diff_val":0
        }
        sq = "select A.regdate as regdate, A.counter_val as ct_val, B.code, C.counter_label as ct_label, C.counter_name as ct_name from common.counting_event as A inner join %s.camera as B inner join %s.counter_label as C on A.device_info=B.device_info and B.code = C.camera_code and A.counter_name=C.counter_name where A.timestamp > %d and C.counter_label='%s' order by A.timestamp asc limit 1" %(MYSQL['db'], MYSQL['db'], arr_count[ct_label]['latest'], ct_label) 

        print (sq)
        cur.execute(sq)
        assoc = cur.fetchone()
        if (assoc) :
            arr[ct_label]['start'] = assoc['regdate']
            arr[ct_label]['abs_val_s'] = assoc['ct_val']

        sq = "select A.regdate, A.counter_val as ct_val, B.code, C.counter_label as ct_label, C.counter_name as ct_name from common.counting_event as A inner join %s.camera as B inner join %s.counter_label as C on A.device_info=B.device_info and B.code = C.camera_code and A.counter_name=C.counter_name where A.timestamp > %d and C.counter_label='%s' order by timestamp desc limit 1" %(MYSQL['db'], MYSQL['db'], arr_count[ct_label]['latest'], ct_label) 

        # print (sq)
        cur.execute(sq)
        assoc = cur.fetchone()
        arr[ct_label]['end'] = assoc['regdate']
        arr[ct_label]['abs_val_e'] = assoc['ct_val']
        arr[ct_label]['diff_val'] = arr[ct_label]['abs_val_e'] - arr[ct_label]['abs_val_s']

    print(arr)
    return False

    for assoc in cur.fetchall():
        arr_t.append(assoc)
  
    # print(arr_t[:3])
    arr_f = []
    for assoc in arr_t:
        label = "%s%s%s" %(assoc['code'], assoc['ct_label'], assoc['ct_name'])
        if not (label in arr_f) :
            arr_f.append(label)
            arr[assoc['ct_label']]["start"] += assoc['ct_val']
    arr_t.reverse()
    # print(arr_t[:3])
    arr_f = []
    for assoc in arr_t:
        label = "%s%s%s" %(assoc['code'], assoc['ct_label'], assoc['ct_name'])
        if not (label in arr_f) :
            arr_f.append(label)
            arr[assoc['ct_label']]["end"] += assoc['ct_val']

    arr['entrance']['count'] = int(arr['entrance']["end"]-arr['entrance']["start"])
    arr['exit']['count'] = int(arr['exit']["end"] - arr['exit']["start"])
    # print(arr)
    return arr

class getDataThread(threading.Thread):
    def __init__(self):
        threading.Thread.__init__(self)
        self.delay = refresh_interval
        self.Running = True
        self.day_flag = 0
        self.min_flag = 0

    def run(self):
        dbcon = dbconMaster()

        with dbcon:
            while self.Running :
                if time.time() // 3600*24 != self.day_flag:
                    yesterdayCount = getRptCounting(dbcon, 1)
                    if not yesterdayCount['entrance']['count'] :
                        yesterdayCount['entrance']['count'] = 0
                    var['number3'].set(yesterdayCount['entrance']['count'])
                    self.day_flag = time.time() // 3600*24

                if time.time()//60 != self.min_flag:
                    rpt_count = getRptCounting(dbcon)
                    nentrance = rpt_count.get('entrance')
                    nexit = rpt_count.get('exit')
                    if nentrance :
                        nentrance = nentrance.get('count')
                    if nexit:
                        nexit = nexit.get('count')
                    self.min_flag = time.time()//60
                        
                if not nentrance or not nexit:
                    time.sleep(2)
                    continue

                rt_count = getRtCounting(dbcon, rpt_count['entrance']['latest'])
                # rt_count = getRtCounting(dbcon, rpt_count)
                print(rt_count)
                num_entrance = nentrance  + rt_count['entrance']['count'] 
                num_exit = nexit + rt_count['exit']['count']

                var['number0'].set(num_entrance)
                var['number1'].set(num_exit)

                var['number2'].set(num_entrance - num_exit)
                if yesterdayCount['entrance']['count']:
                    var['number4'].set(str(int(num_entrance * 10000/yesterdayCount['entrance']['count'])/100) + " %")
                else :
                    var['number4'].set(str(""))
                dbcon.commit()
                time.sleep(2)
                
    def stop(self):
        self.Running = False


def stop_fullscreen(event=None):
    global th
    print ("ESCAPE")
    th.stop()
    time.sleep(2)
    root.overrideredirect(False)
    root.attributes("-fullscreen", False)
    
    root.destroy()
    sys.exit()

def putSection(label, arr):
    global menus
    global var
    var[arr['name']] = StringVar()
    label.configure(textvariable = var[arr['name']] )
    if arr.get('enable') == 'no':
        return False
    
    for key in arr:
        if key == 'text':
            var[arr['name']].set(arr['text'])
        elif key =='font':
            # label.configure(font = arr['font'])
            label.configure(font=tuple(arr['font']))
        elif key == 'color':
            label.configure(fg=arr['color'][0], bg=arr['color'][1])
        elif key == 'width':
            label.configure(width=int(arr['width']))
        elif key == 'height':
            label.configure(height=int(arr['height']))
        elif key == 'position':
            if isinstance(arr['position'][0], int):
                label.place(x=int(arr['position'][0]))
            elif arr['position'][0] == "center":
                l = 0
                for c in arr['text']:
                    if ord(c) >256:
                        l += int(arr['font'][1]*0.8)
                    else :
                        l += int(arr['font'][1]*0.4)
                p = int(screen_width / 2) - l
                label.place(x=p)

            if isinstance(arr['position'][1], int):
                label.place(y=int(arr['position'][1]))
        elif key == 'anchor':
            label.configure(anchor=arr['anchor'])
        elif key == 'padx':
            label.configure(padx=arr['padx'])
        elif key == 'pady':
            label.configure(pady=arr['pady'])

def func_mouse(e):
    print(e.x, e.y)

if __name__ == '__main__':
    menus = dict()
    var = dict()
    th = None

    with open ('rtScreen.json', 'r', encoding='utf8')  as f:
        body = f.read()
    arr_t = json.loads(body)
    arrs = arr_t['screen']
    MYSQL = arr_t['mysql']
    print (MYSQL)

    ref_start_timestamp = arr_t["ref_start_time"].get('hour') *3600 + arr_t["ref_start_time"].get('min') *60
    ref_end_timestamp = arr_t["ref_end_time"].get('hour') *3600 + arr_t["ref_end_time"].get('min') *60

    refresh_interval = arr_t.get("refresh_interval")
    if not refresh_interval:
        refresh_interval = 2


    root =Tk()

    screen_width = root.winfo_screenwidth()
    screen_height = root.winfo_screenheight()

    root.geometry("%dx%d+0+0" %((screen_width), (screen_height)))

    root.bind('<Escape>', stop_fullscreen)
    root.bind('<Button-1>', func_mouse)
    root.configure(background="black")
    root.resizable (False, False)
    root.overrideredirect(True)
    
    label_title = Label(root)
    putSection(label_title, arrs['title'])

    for i, sect in enumerate(arrs['sections']):
        menus[sect['name']] = Label(root)
        putSection(menus[sect['name']], sect)

    th = getDataThread()
    th.start()
    root.mainloop()

sys.exit()

