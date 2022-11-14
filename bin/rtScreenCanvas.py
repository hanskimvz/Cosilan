# Copyright (c) 2022, Hans kim

# Redistribution and use in source and binary forms, with or without
# modification, are permitted provided that the following conditions are met:
# 1. Redistributions of source code must retain the above copyright
# notice, this list of conditions and the following disclaimer.
# 2. Redistributions in binary form must reproduce the above copyright
# notice, this list of conditions and the following disclaimer in the
# documentation and/or other materials provided with the distribution.

# THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND
# CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES,
# INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
# MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
# DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR
# CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
# SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
# BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
# SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
# INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
# WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
# NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
# OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

import time, os, sys
import locale
import uuid
import shutil
import json, base64, re
import pymysql

from tkinter import *
from tkinter import ttk
from tkinter import filedialog
import cv2 as cv
import numpy as np
from PIL import ImageTk, Image
import threading

_ROOT_DIR = os.path.abspath(os.path.dirname(sys.argv[0]))
os.chdir(_ROOT_DIR)

TZ_OFFSET = 3600*8

ARR_CRPT = dict()
ARR_CONFIG = dict()
ARR_SCREEN = list()

lang = dict()
ths = None  # screen thread
thd = None  # data thread
thv = None  # video thread

menus = dict()
var = dict()


oWin = None  # option window

####################################################################################################################
###########################################        BASIC Functions        ##########################################
####################################################################################################################
def getMac():
	mac = "%012X" %(uuid.getnode())
	return mac

def dbconMaster(host='', user='', password='',  charset = 'utf8', port=0): #Mysql
    global ARR_CONFIG
    if not host:
        host=ARR_CONFIG['mysql']['host']
    if not user :
        user = ARR_CONFIG['mysql']['user']
    if not password:
        password = ARR_CONFIG['mysql']['password']
    if not port:
        port = int(ARR_CONFIG['mysql']['port'])

    try:
        dbcon = pymysql.connect(host=host, user=str(user), password=str(password),  charset=charset, port=port)
    except pymysql.err.OperationalError as e :
        print (str(e))
        return None
    return dbcon   

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
    
def getSquare(cursor):
    sq = "select * from %s.square " %(ARR_CONFIG['mysql']['db'])
    cursor.execute(sq)
    return cursor.fetchall()

def getStore(cursor):
    sq = "select * from %s.store " %(ARR_CONFIG['mysql']['db'])
    cursor.execute(sq)
    return cursor.fetchall()

def getCamera(cursor):
    sq = "select * from %s.camera " %(ARR_CONFIG['mysql']['db'])
    cursor.execute(sq)
    return cursor.fetchall()

def getCounterLabel(cursor):
    sq = "select * from %s.counter_label " %(ARR_CONFIG['mysql']['db'])
    cursor.execute(sq)
    return cursor.fetchall()

def getDevices(cursor, device_info=''):
    sq = "select pk, device_info, usn, product_id, lic_pro, lic_surv, lic_count, face_det, heatmap, countrpt, macsniff, write_cgi_cmd, initial_access, last_access, db_name, url, method, user_id, user_pw from common.params "
    if device_info:
        sq += " where device_info='%s'" %device_info
    else :
        sq += " where db_name='%s'" %(ARR_CONFIG['mysql']['db'])
    cursor.execute(sq)
    return cursor.fetchall()

def getSnapshot(cursor, device_info):
    sq = "select body from common.snapshot where device_info='%s' order by regdate desc limit 1" %(device_info)
    cursor.execute(sq)
    body = cursor.fetchone()

    if body:
        return body[0]
    return False


def loadConfig():
    global lang, ARR_CONFIG

    with open ('%s\\rtScreen.json' %_ROOT_DIR, 'r', encoding='utf8')  as f:
        body = f.read()
    ARR_CONFIG = json.loads(body)        

    LOCALE = locale.getdefaultlocale()
    if LOCALE[0] == 'zh_CN':
        selected_language = 'Chinese'
    elif LOCALE[0] == 'ko_KR':
        selected_language = 'Korean'
    else :
        selected_language = 'English'

    for s in ARR_CONFIG['language']:
        lang[s['key']] = s[selected_language]

    # MYSQL = ARR_CONFIG['mysql']
    if not ARR_CONFIG['refresh_interval'] :
        ARR_CONFIG['refresh_interval'] = 2

    if not ARR_CONFIG['full_screen']:
        ARR_CONFIG['full_screen'] = "no"

# def getConfig():
#     with open ('%s\\rtScreen.json' %_ROOT_DIR, 'r', encoding='utf8')  as f:
#         body = f.read()
#     arr = json.loads(body)        

#     LOCALE = locale.getdefaultlocale()
#     if LOCALE[0] == 'zh_CN':
#         selected_language = 'Chinese'
#     elif LOCALE[0] == 'ko_KR':
#         selected_language = 'Korean'
#     else :
#         selected_language = 'English'

#     for s in arr['language']:
#         lang[s['key']] = s[selected_language]

#     if not arr['refresh_interval'] :
#         arr['refresh_interval'] = 2

#     if not arr['full_screen']:
#         arr['full_screen'] = "no"
    
#     return arr

def writeConfig():
    global ARR_CONFIG
    json_str = json.dumps(ARR_CONFIG, ensure_ascii=False, indent=4, sort_keys=True)
    with open("%s\\rtScreen.json" %_ROOT_DIR, "w", encoding="utf-8") as f:
        f.write(json_str)

# def getTemplate(template_doc):
#     with open ("%s\\%s" %(_ROOT_DIR, template_doc), 'r', encoding="utf-8") as f:
#         body = f.read()
#         print ('readed template')
#     return json.loads(body)


# def getScreenData():
#     global ARR_CONFIG, ARR_SCREEN
#     with open ("%s\\%s" %(_ROOT_DIR, ARR_CONFIG['template']), 'r', encoding="utf-8") as f:
#         body = f.read()
#         print ('readed template')
#     ARR_SCREEN = json.loads(body)

def loadTemplate():
    global ARR_CONFIG, ARR_SCREEN
    with open ("%s\\%s" %(_ROOT_DIR, ARR_CONFIG['template']), 'r', encoding="utf-8") as f:
        body = f.read()
        print ('readed template')
    ARR_SCREEN = json.loads(body)

def writeTemplate():
    global ARR_CONFIG, ARR_SCREEN
    json_str = json.dumps(ARR_SCREEN, ensure_ascii=False, indent=4, sort_keys=True)
    # print(json_str)
    with open("%s\\%s" %(_ROOT_DIR, ARR_CONFIG['template']), "w", encoding="utf-8") as f:
        f.write(json_str)


# def writeScreen():
#     global ARR_CONFIG, ARR_SCREEN
#     json_str = json.dumps(ARR_SCREEN, ensure_ascii=False, indent=4, sort_keys=True)
#     # print(json_str)
#     with open("%s\\%s" %(_ROOT_DIR, ARR_CONFIG['template']), "w", encoding="utf-8") as f:
#         f.write(json_str)


# def writeScreenData():
#     global ARR_CONFIG, ARR_SCREEN
#     json_str = json.dumps(ARR_SCREEN, ensure_ascii=False, indent=4, sort_keys=True)
#     # print(json_str)
#     with open("%s\\%s" %(_ROOT_DIR, ARR_CONFIG['template']), "w", encoding="utf-8") as f:
#         f.write(json_str)

def getWorkingHour(cursor):
    arr_sq = list()
    sq_work = ""
    sq = "select code, open_hour, close_hour, apply_open_hour from %s.store " %(ARR_CONFIG['mysql']['db'])
    # print (sq)
    cursor.execute(sq)
    for row in cursor.fetchall():
        # print(db_name, row)
        if row[3]=='y' and  row[1] < row[2] :
            arr_sq.append("(store_code='%s' and hour>=%d and hour < %d)" %(row[0], row[1], row[2]) )
        else :
            arr_sq.append("(store_code='%s')" %row[0])
    
    if arr_sq:
        sq_work = ' or '.join(arr_sq)
        sq_work = "and (%s)" %sq_work
    return sq_work

def updateRptCounting(cursor):
    global ARR_CRPT
    ARR_CRPT = dict()
    # sq_work = getWorkingHour(cursor)
    # print("sqwork:", sq_work)
    sq_work = ""
    
    ts_now = int(time.time() + TZ_OFFSET)
    tsm = time.gmtime(ts_now)
    arr_ref = [
        {
            "ref_date": 'today',
            "start_ts" : int(ts_now //(3600*24)) * 3600*24,
            "end_ts" : int(time.time() + TZ_OFFSET),
        },
        {
            "ref_date" : 'yesterday',
            "start_ts" :  int(ts_now //(3600*24)) * 3600*24 - 3600*24,
            "end_ts" : int(ts_now //(3600*24)) * 3600*24,
            
        },
        {
            "ref_date" : 'thismonth',
            "start_ts" : int(time.mktime((tsm.tm_year, tsm.tm_mon, 1, 0, 0, 0, 0, 0, 0)) + TZ_OFFSET),
            "end_ts" : ts_now
        },
        {
            "ref_date" : 'lastmonth',
            "start_ts" : int(time.mktime((tsm.tm_year, tsm.tm_mon-1, 1, 0, 0, 0, 0, 0, 0)) + TZ_OFFSET),
            "end_ts" : int(time.mktime((tsm.tm_year, tsm.tm_mon, 1, 0, 0, 0, 0, 0, 0)) + TZ_OFFSET),
        },

        {
            "ref_date" : 'thisyear',
            "start_ts" : int(time.mktime((tsm.tm_year, 1, 1, 0, 0, 0, 0, 0, 0)) + TZ_OFFSET),
            "end_ts" : ts_now
        },
        {
            "ref_date" : 'lastyear',
            "start_ts" : int(time.mktime((tsm.tm_year-1, 1, 1, 0, 0, 0, 0, 0, 0)) + TZ_OFFSET),
            "end_ts" : int(time.mktime((tsm.tm_year, 1, 1, 0, 0, 0, 0, 0, 0)) + TZ_OFFSET),
        },
        {
            "ref_date" : 'total',
            "start_ts" : int(time.mktime((2000, 1, 1, 0, 0, 0, 0, 0, 0)) + TZ_OFFSET),
            "end_ts" : ts_now
        }

    ]
    for arr in arr_ref:
        # print(arr)
        
        sq = "select device_info, counter_label, sum(counter_val) as sum, max(timestamp) as latest_ts from %s.count_tenmin where timestamp >= %d and timestamp < %d %s group by counter_label, device_info" %( ARR_CONFIG['mysql']['db'], arr['start_ts'], arr['end_ts'], sq_work)
        # print(arr['ref_date'], sq)
        cursor.execute(sq)
        for row in cursor.fetchall():
            # print (row)
            if not arr['ref_date'] in ARR_CRPT:
                ARR_CRPT[arr['ref_date']] = dict()
            if not row[0] in ARR_CRPT[arr['ref_date']]:
                ARR_CRPT[arr['ref_date']][row[0]] = dict()
            if not row[1] in ARR_CRPT[arr['ref_date']][row[0]]:
                ARR_CRPT[arr['ref_date']][row[0]][row[1]] = dict()

            ARR_CRPT[arr['ref_date']][row[0]][row[1]]['counter_val'] = row[2]
            ARR_CRPT[arr['ref_date']][row[0]][row[1]]['latest'] = row[3]
            ARR_CRPT[arr['ref_date']][row[0]][row[1]]['datetime'] = time.strftime("%Y-%m-%d %H:%M:%S", time.gmtime(row[3]))

            if not 'all' in ARR_CRPT[arr['ref_date']]:
                ARR_CRPT[arr['ref_date']]['all'] = dict()
            if not row[1] in ARR_CRPT[arr['ref_date']]['all']:
                ARR_CRPT[arr['ref_date']]['all'][row[1]] = {'counter_val':0, 'latest':0}


            ARR_CRPT[arr['ref_date']]['all'][row[1]]['counter_val'] += row[2]
            if (row[3] > ARR_CRPT[arr['ref_date']]['all'][row[1]]['latest']):
                ARR_CRPT[arr['ref_date']]['all'][row[1]]['latest'] = row[3]
                ARR_CRPT[arr['ref_date']]['all'][row[1]]['datetime'] = time.strftime("%Y-%m-%d %H:%M:%S", time.gmtime(row[3]))

    # for x in ARR_CRPT:
    #     for y in ARR_CRPT[x]:
    #         print (x, y, ARR_CRPT[x][y])
    
def getRtCounting(cursor):
    arr_t = dict()
    ct_mask =  list()
    if not ARR_CRPT.get('today'):
        return False

    for dev_info in ARR_CRPT['today']:
        for ct in ARR_CRPT['today'][dev_info]:
            if dev_info == 'all':
                continue
            ct_mask.append("(device_info = '%s' and counter_label='%s' and timestamp>%d)" %(dev_info, ct, ARR_CRPT['today'][dev_info][ct]['latest']))

    if (ct_mask) :
        sq_s = ' or '.join(ct_mask)
        sq_s = ' and (%s)' %(sq_s)
    sq = "select timestamp, counter_val, device_info, counter_label, counter_name from common.counting_event where db_name='%s' %s  order by timestamp asc " %(ARR_CONFIG['mysql']['db'], sq_s) 
    # print (sq)
    cursor.execute(sq)
    for row in cursor.fetchall():
        if not row[2] in arr_t:
            arr_t[row[2]] = dict()
        if not row[3] in arr_t[row[2]]:
            arr_t[row[2]][row[3]] = {'min': row[1], 'max':0, 'diff':0} 

        arr_t[row[2]][row[3]]['max'] = row[1]
        arr_t[row[2]][row[3]]['diff'] = abs(row[1] - arr_t[row[2]][row[3]]['min'])

        if not 'all' in arr_t:
            arr_t['all'] = dict()
        if not row[3] in arr_t['all']:
            arr_t['all'][row[3]] = {'min':0, 'max':0, 'diff':0} 

    for dev_info in arr_t:
        if dev_info == 'all':
            continue
        for ct in arr_t[dev_info]:
            arr_t['all'][ct]['diff'] += arr_t[dev_info][ct]['diff'] 

    return arr_t

def parseRule(ss):
    regex= re.compile(r"(\w+\s*:\s*\w+)", re.IGNORECASE)
    calc_regex= re.compile(r"(\w+)\(", re.IGNORECASE)
    m = calc_regex.search(ss)
    calc = m.group(1) if m else 'sum'
    if not calc in ['sum', 'diff', 'div', 'percent']:
        return False
    arr = list()
    for m in regex.finditer(ss):
        dt, ct = m.group().split(":")
        arr.append((dt.strip(), ct.strip()))
    if not arr:
        return False
    return (calc, arr)

def getNumberData(cursor):
    global ARR_CRPT, ARR_SCREEN
    arr_number = list()
   
    for n in ARR_SCREEN:
        if n['name'].startswith('number'):
            exp = parseRule(n['rule'])
            if not (exp):
                continue
            calc, rule = exp
            arr_number.append({
                "name": n['name'],
                "device_info": n['device_info'],
                "calc": calc,
                "rule": rule,
                "text": 0,
                "flag": n['flag']
            })
    arr_rt = getRtCounting(cursor)
    for i, arr in enumerate(arr_number):
        if arr['flag'] == 'n':
            continue
        
        if arr.get('device_info'):
            dev_info = arr['device_info']
        else :
            arr_number[i]['text'] = 0
            continue
        num=0
        n = 0
        for j, (dt, ct) in enumerate(arr['rule']):
            if ARR_CRPT.get(dt) and ARR_CRPT[dt].get(dev_info) and ARR_CRPT[dt][dev_info].get(ct):
                n = ARR_CRPT[dt][dev_info][ct]['counter_val']
            else :
                print ("Error on rpt >> dt:", dt, "dev_info:", dev_info, "ct:", ct)

            if  not (dt == 'yesterday' or  dt == 'lastmonth' or dt == 'lastyear'):
                if arr_rt :
                    if arr_rt.get(dev_info) and arr_rt[dev_info].get(ct):
                        n += arr_rt[dev_info][ct]['diff']
                    else :
                        print ("Error on rt >> dev_info:", dev_info, "ct:", ct)
                else:
                    print ("Error on rt >> arr_rt is null")
            if j == 0:
                num = n
            
            elif arr['calc'] == 'sum':
                num += n
            
            elif arr['calc'] == 'diff':
                num -= n
                    
        if arr['calc'] == 'div' or arr['calc'] == 'percent' and n:
                num = "%3.2f %%"  %(num/n *100) if  arr['calc'] == 'percent' else "%3.2f"  %(num/n)

        arr_number[i]['text'] = num

    for n in arr_number:
        print (n)
    
    return arr_number  





######################################################################################################################################################
############################################## Option Config #########################################################################################
######################################################################################################################################################

def restartProgram():
    sys.stdout.flush()
    os.execv(sys.executable, ["python3.exe"] + sys.argv)


def edit_option(e=None):
    print(e)
    global oWin, lang, var, ARR_CONFIG, root

    if oWin:
        oWin.destroy()
    oWin = Toplevel(root)		
    oWin.title("Configuration")
    oWin.geometry("300x400+%d+%d" %(int(screen_width/2-150), int(screen_height/2-200)))
    oWin.resizable(False, False)

    var['background'] = IntVar()
    var['refresh_interval'] = StringVar()
    var['full_screen'] = IntVar()
    var['template'] = StringVar()
    var['message_str'] = StringVar()

    for key in ARR_CONFIG['mysql']:
        var[key] = StringVar()
        var[key].set(ARR_CONFIG['mysql'][key])

    btnFrame = Frame(oWin)
    btnFrame.pack(side="bottom", pady=10)
    Button(btnFrame, text=lang['close_option'], command=closeOption, width=16).pack(side="left", padx=5)
    Button(btnFrame, text=lang['exit_program'], command=exitProgram, width=16).pack(side="right", padx=5)

    dbFrame = Frame(oWin)
    dbFrame.pack(side="top", pady=10)

    Label(dbFrame, text=lang['db_server']).grid(row=0, column=0, sticky="w", pady=2, padx=4)
    Label(dbFrame, text=lang['user']).grid(row=1, column=0, sticky="w", pady=2, padx=4)
    Label(dbFrame, text=lang['password']).grid(row=2, column=0, sticky="w", pady=2, padx=4)
    Label(dbFrame, text=lang['charset']).grid(row=3, column=0, sticky="w", pady=2, padx=4)
    Label(dbFrame, text=lang['port']).grid(row=4, column=0, sticky="w", pady=2, padx=4)
    Label(dbFrame, text=lang['db_name']).grid(row=5, column=0, sticky="w", pady=2, padx=4)
    Label(dbFrame, text=lang['background']).grid(row=6, column=0, sticky="w", pady=2, padx=4)
    Label(dbFrame, text=lang['refresh_interval']).grid(row=7, column=0, sticky="w", pady=2, padx=4)
    Label(dbFrame, text=lang['full_screen']).grid(row=8, column=0, sticky="w", pady=2, padx=4)
    Label(dbFrame, text=lang['template']).grid(row=9, column=0, sticky="w", pady=2, padx=4)

    Entry(dbFrame, textvariable=var['host']).grid(row=0, column=1, ipadx=3)
    Entry(dbFrame, textvariable=var['user']).grid(row=1, column=1, ipadx=3)
    Entry(dbFrame, textvariable=var['password']).grid(row=2, column=1, ipadx=3)
    Entry(dbFrame, textvariable=var['charset']).grid(row=3, column=1, ipadx=3)
    Entry(dbFrame, textvariable=var['port']).grid(row=4, column=1, ipadx=3)
    Entry(dbFrame, textvariable=var['db']).grid(row=5, column=1, ipadx=3)

    cfb = Checkbutton(dbFrame, variable=var['background'])
    cfb.grid(row=6, column=1, sticky="w")
    if ARR_CONFIG['background'] == 'yes':
        cfb.select()
    
    Button(dbFrame, command=saveBG, text=lang['url']).grid(row=6, column=1)

    Entry(dbFrame, textvariable=var['refresh_interval']).grid(row=7, column=1, ipadx=3)
    cfs = Checkbutton(dbFrame, variable=var['full_screen'])
    cfs.grid(row=8, column=1, sticky="w")
    if ARR_CONFIG['full_screen'] == 'yes':
        cfs.select()
    # Entry(dbFrame, textvariable=var['template']).grid(row=8, column=1, ipadx=3)
    listTemplates = []
    for x in os.listdir(_ROOT_DIR):
        if x.startswith("template"):
            listTemplates.append(x)
    var['template'] = ttk.Combobox(dbFrame, width=16, values=listTemplates)
    var['template'].grid(row=9, column=1, ipadx=3)
    Button(dbFrame, text=lang['save_changes'], command=saveConfig, width=16).grid(row=10, column=0, columnspan=2)

    var['refresh_interval'].set(ARR_CONFIG['refresh_interval'])
    for i, x in enumerate(listTemplates):
        if x == ARR_CONFIG['template']:
            var['template'].current(i)

    Message(oWin, textvariable = var['message_str'], width= 300,  bd=0, relief=SOLID, foreground='red').pack(side="top")

def closeOption():
    global oWin, ths
    oWin.destroy()
    oWin = None
    ths.delay = ARR_CONFIG['refresh_interval']*10

def saveConfig():
    global ARR_CONFIG, ARR_SCREEN
    need_restart = False
    message ("")
    chMysql = False
    for key in ARR_CONFIG['mysql']:
        if str(ARR_CONFIG['mysql'][key]).strip() != str(var[key].get()).strip():
            print ("%s : %s" %(ARR_CONFIG['mysql'][key], var[key].get()))
            chMysql = True
            break
    if chMysql:
        try:
            ret = dbconMaster(
                host = str(var['host'].get().strip()),
                user = str(var['user'].get().strip()), 
                password = str(var['password'].get().strip()),
                charset = str(var['charset'].get().strip()),
                port = int(var['port'].get().strip())
            )
            print (ret.ping(reconnect=False))
            need_restart = True
        except Exception as e:
            print ("MYSQL Error")
            print (e)
            message (lang.get("check_mysql_conf"))
            return False

        for key in ARR_CONFIG['mysql']:
            ARR_CONFIG['mysql'][key] = str(var[key].get()).strip()

    try:
        ARR_CONFIG['refresh_interval'] = int(var['refresh_interval'].get())
    except:
        message (lang.get("refresh_time_error"))
        return False

    if ARR_CONFIG['template'] != var['template'].get().strip():
        ARR_CONFIG['template'] = var['template'].get().strip()
        print ("template changed")
        need_restart = True

    fx = "yes" if var['full_screen'].get() else "no"
    if ARR_CONFIG['full_screen'] != fx:
        ARR_CONFIG['full_screen'] = fx
        if ARR_CONFIG['full_screen'] == "yes":
            # root.overrideredirect(True)
            root.attributes("-fullscreen", True)
            root.resizable (False, False)
        else :
            root.overrideredirect(False)
            root.attributes("-fullscreen", False)
            root.resizable (True, True)

    fb = "yes" if var['background'].get() else "no"
    if ARR_CONFIG['background'] != fb:
        ARR_CONFIG['background'] = fb
        need_restart = True

    if fb == "yes" and var.get('bgTemp'):
        shutil.copyfile(var['bgTemp'], "%s\\bg.jpg" %_ROOT_DIR)
        need_restart = True

    writeConfig()
    message("saved")
    if need_restart:
        restartProgram()

def saveBG():
    global oWin, var
    fname = filedialog.askopenfilename(title="Select imagefile", filetypes=[("image", ".jpeg"),("image", ".png"),("image", ".jpg"),])
    print(fname)
    var['bgTemp'] = fname
    oWin.lift()

def message(strn):
    var['message_str'].set(strn)


#########################################################################################################
############################################## Screen Edit ##############################################
#########################################################################################################
def getScreenByName(name):
    global ARR_SCREEN
    for i, x in enumerate(ARR_SCREEN):
        if x['name'] == name:
            return ARR_SCREEN[i]
    
            
            

def edit_screen(e= None):
    global ARR_SCREEN, menus, oWin, bg_canvas
    minx = screen_height**2 + screen_width **2
    selLabel = None

    for m in menus:
        p = bg_canvas.coords(menus[m])
        b = bg_canvas.bbox(menus[m])
        if not p or not b:
            continue
        print(m, e.x, e.y, p, b)
        if (e.x >= b[0] and e.x <= b[2] and e.y >= b[1] and e.y <= b[3]):
            selLabel = m
            # x = abs(p[0]-e.x)
            # y = abs(p[1]-e.y)
            # dist = (x**2 + y**2 )
            # if dist < minx:
            #     minx = dist
            #     selLabel = m

    ths.delay = 1
    print(selLabel, minx)

    # if selLabel:
    #     for m in menus:
    #         menus[m].configure(borderwidth=0, relief="groove")
    #     menus[selLabel].configure(borderwidth=2, relief="groove")

    if oWin: 
        oWin.destroy()

    oWin = Toplevel(root)		
    oWin.title("Edit Screen")
    oWin.geometry("260x600+%d+%d" %(int(screen_width/2-150), int(screen_height/2-200)))
    oWin.resizable(True, True)

    btnFrame = Frame(oWin)
    btnFrame.pack(side="bottom", pady=10)
    Button(btnFrame, text=lang['close_option'], command=closeOption, width=16).pack(side="left", padx=5)

    if selLabel:
        frameScreen(oWin, selLabel)
    else:
        frameUnFlag(oWin)

def frameUnFlag(win):
    global ARR_SCREEN
    useFlag=dict()
    def updateFlag():
        need_restart = False
        for m in useFlag:
            # print (m, useFlag[m].get())
            for i, x in  enumerate(ARR_SCREEN):
                if useFlag[m].get() and x['name'] == m:
                    print (ARR_SCREEN[i])
                    ARR_SCREEN[i]['flag'] = 'y'
                    need_restart =  True

        if need_restart:            
            writeTemplate()
            # restartProgram()

    dbFrame = Frame(win)
    dbFrame.pack(side="top", pady=10)

    for i, x in enumerate(ARR_SCREEN):
        if x['flag'] == 'n':
            print (x['name'])
            useFlag[x['name']] = IntVar()
            Label(dbFrame, text=x['name'], anchor="w").grid(row=i, column=0)
            Checkbutton(dbFrame, variable=useFlag[x['name']]).grid(row=i, column=1)

    Button(dbFrame, text=lang['save_changes'], command=updateFlag, width=16).grid(row=i, column=0, columnspan=2)            

 
def frameScreen(win, selLabel):
    global ARR_SCREEN, ARR_CRPT, menus
    # print(getScreenByName(selLabel))

    dbFrame = Frame(win)
    dbFrame.pack(side="top", pady=10)

    listDev = set()
    listDevice = list()
    listFontFamily = ['simhei', 'arial', 'fangsong', 'simsun', 'gulim', 'batang', 'ds-digital','bauhaus 93', 'HP Simplified' ]
    listFontShape  = ['normal', 'bold', 'italic']
    listFontColor  = ['white', 'black', 'orange', 'blue', 'red', 'green', 'purple', 'grey', 'yellow', 'pink']


    # arr = getCRPT()
    for dt in ARR_CRPT:
        for x in ARR_CRPT[dt]:
            if x == 'all':
                continue
            listDev.add(x)
    listDevice = list(listDev)
    listDev = list(listDev)
    listDevice.insert(0, "all") # include all
    

    arr_lvar = ['selLabel', 'display', 'font', 'fontsize', 'fontshape', 'color', 'bgcolor', 'width', 'height', 'posX', 'posY', 'padX', 'padY', 'device_info', 'rule','use', 'url']
    lvar = dict()
    elb = dict()
    ent = dict()

    for x in arr_lvar:
        lvar[x] = StringVar()
    
    # def updateEntry(selLabel):
    #     message("")
    #     for x in ARR_SCREEN:
    #         if x['name'] == selLabel:
                
    #             ent['posX'].grid(row=4, column=0)
    #             ent['posY'].grid(row=4, column=1, columnspan=2)
    #             # elb['width'].grid(row=6, column=0, sticky="w", pady=2, padx=4)
    #             # ent['width'].grid(row=6, column=1, sticky="w", ipadx=3)
    #             # ent['height'].grid(row=6, column=2, sticky="w", ipadx=3)
    #             # elb['padding'].grid(row=7, column=0, sticky="w", pady=2, padx=4)
    #             # ent['padX'].grid(row=7, column=1, sticky="w", ipadx=3)
    #             # ent['padY'].grid(row=7, column=2, sticky="w", ipadx=3)
    #             elb['use'].grid(row=10, column=0, sticky="w", pady=2, padx=4)
    #             ent['use'].grid(row=10, column=1, sticky="w")

    #             lvar['width'].set(x.get('size')[0])
    #             lvar['height'].set(x.get('size')[1])
    #             lvar['posX'].set(x.get('position')[0])
    #             lvar['posY'].set(x.get('position')[1])
    #             lvar['padX'].set(x.get('padding')[0])
    #             lvar['padY'].set(x.get('padding')[1])

    #             if x.get('flag') == 'y':
    #                 ent['use'].select()
    #             else :
    #                 ent['use'].deselect()

    #             if selLabel.startswith('picture') :
    #                 elb['width'].grid(row=6, column=0, sticky="w", pady=2, padx=4)
    #                 ent['width'].grid(row=6, column=1, sticky="w", ipadx=3)
    #                 ent['height'].grid(row=6, column=2, sticky="w", ipadx=3)

    #                 elb['url'].grid(row=1, column=0, sticky="w", pady=2, padx=4)
    #                 ent['url'].grid(row=1, column=1, columnspan=2, sticky="w")
    #                 lvar['url'].set(x.get('url'))

    #             elif selLabel.startswith('snapshot') or selLabel.startswith('video'):
    #                 elb['width'].grid(row=6, column=0, sticky="w", pady=2, padx=4)
    #                 ent['width'].grid(row=6, column=1, sticky="w", ipadx=3)
    #                 ent['height'].grid(row=6, column=2, sticky="w", ipadx=3)

    #                 elb['device_info'].grid(row=1, column=0, sticky="w", pady=2, padx=4)
    #                 ent['device_info'].grid(row=1, column=1, columnspan=2, sticky="w")
    #                 lvar['device_info'].set(x.get('device_info'))
    #                 ent['device_info'].configure(values=listDev)
    #                 for i, ft in enumerate(listDev):
    #                     if x.get('device_info') == ft:
    #                         ent['device_info'].current(i)
                    

    #             else :
    #                 btn_f_p.grid(row=1, column=1)
    #                 btn_f_m.grid(row=1, column=2)
    #                 ent['fontsize'].grid(row=4, column=3)

    #                 elb['font'].grid(row=2, column=0, sticky="w", pady=2, padx=4)
    #                 ent['font'].grid(row=2, column=1, columnspan=2, sticky="w")
    #                 elb['fontshape'].grid(row=3, column=0, sticky="w", pady=2, padx=4)
    #                 ent['fontshape'].grid(row=3, column=1, columnspan=2, sticky="w")
    #                 elb['color'].grid(row=4, column=0, sticky="w", pady=2, padx=4)
    #                 ent['color'].grid(row=4, column=1, columnspan=2, sticky="w")
    #                 # elb['bgcolor'].grid(row=5, column=0, sticky="w", pady=2, padx=4)
    #                 # ent['bgcolor'].grid(row=5, column=1, columnspan=2, sticky="w")

    #                 for i, ft in enumerate(listFontFamily):
    #                     if x.get('font')[0] == ft:
    #                         ent['font'].current(i)
    #                 lvar['fontsize'].set(x.get('font')[1])

    #                 for i, ft in enumerate(listFontShape):
    #                     if x.get('font')[2] == ft:
    #                         ent['fontshape'].current(i)

    #                 for i, ft in enumerate(listFontColor):
    #                     if x.get('color')[0] == ft:
    #                         ent['color'].current(i)

    #                 for i, ft in enumerate(listFontColor):
    #                     if x.get('color')[1] == ft:
    #                         ent['bgcolor'].current(i)

    #                 if selLabel.startswith('number'):
    #                     elb['device_info'].grid(row=8, column=0, sticky="w", pady=2, padx=4)
    #                     ent['device_info'].grid(row=8, column=1, columnspan=2, sticky="w")
    #                     elb['rule'].grid(row=9, column=0, sticky="w", pady=2, padx=4)
    #                     ent['rule'].grid(row=9, column=1, columnspan=2, sticky="w", ipadx=3)
    #                     ent['device_info'].configure(values=listDevice)
    #                     for i, ft in enumerate(listDevice):
    #                         if x.get('device_info') == ft:
    #                             ent['device_info'].current(i)                        
    #                     # for i, ft in enumerate(listDevice):
    #                     #     if x.get('device_info') == ft:
    #                     #         ent['device_info'].current(i)

    #                     lvar['rule'].set(x.get('rule'))
    #                 else :
    #                     elb['display'].grid(row=1, column=0, sticky="w", pady=2, padx=4)
    #                     ent['display'].grid(row=1, column=1, columnspan=2, sticky="w", ipadx=3)
    #                     lvar['display'].set(x.get('text'))

    def saveScreen():
        global ARR_CONFIG
        arr = ARR_SCREEN
        if not selLabel:
            return False
        for i, r in enumerate(arr):
            if r['name'] == selLabel:
                if not (lvar['padX'].get().isnumeric() and lvar['padY'].get().isnumeric()):
                    print ("padding type error")
                    message("padding type error")
                    return False
                if not (lvar['posX'].get().isnumeric() and lvar['posY'].get().isnumeric()):
                    print ("position type error")
                    message("position type error")
                    return False
                if not (lvar['width'].get().isnumeric() and lvar['height'].get().isnumeric()):
                    print ("size type error")
                    message("size type error")
                    return False

                arr[i]['padding'] = [int(lvar['padX'].get()), int(lvar['padY'].get())]
                arr[i]['position'] = [int(lvar['posX'].get()), int(lvar['posY'].get())]
                arr[i]['size'] = [int(lvar['width'].get()), int(lvar['height'].get())]
                arr[i]['flag'] = 'y' if int(lvar['use'].get()) else 'n'

                if selLabel.startswith('picture') or selLabel.startswith('video'):
                    arr[i]['url'] = lvar['url'].get()

                elif selLabel.startswith('snapshot'):
                    if ent['device_info'].get() == 'all':
                        continue
                    arr[i]['device_info'] = ent['device_info'].get()

                else:
                    if not (lvar['fontsize'].get().isnumeric()):
                        print ("fontsize type error")
                        message("fontsize type error")
                        return False
                    arr[i]['font'] = [ent['font'].get(), int(lvar['fontsize'].get()), ent['fontshape'].get()]
                    arr[i]['color'] = [ent['color'].get(), ent['bgcolor'].get()]

                    if selLabel.startswith('number'):
                        if not parseRule(lvar['rule'].get()):
                            print (parseRule(lvar['rule'].get()))
                            message("rule error \n sum/diff/div/percent(date:counter_label,), \nEx: sum(today:entrance, today:exit)")
                            return False
                        arr[i]['text'] = ""
                        arr[i]['device_info'] = ent['device_info'].get()
                        arr[i]['rule'] = lvar['rule'].get()
                    else :
                        arr[i]['text'] = lvar['display'].get()
                    
        # print (arr)
        writeTemplate()
        message("saved")

    def movePos(d, v):
        lvar[d].set(str(int(lvar[d].get()) + int(v)))
        saveScreen()

    def fontSizeU():
        lvar['fontsize'].set(str(int(lvar['fontsize'].get())+1))
        saveScreen()
    def fontSizeD():
        lvar['fontsize'].set(str(int(lvar['fontsize'].get())-1))
        saveScreen()

    def browseFile():
        global oWin
        fdir = os.path.dirname(lvar['url'].get())
        fname = filedialog.askopenfilename(initialdir=fdir , title="Select imagefile", filetypes=[("image", ".jpeg"),("image", ".png"),("image", ".jpg"),])
        print(fname)
        lvar['url'].set(fname)
        oWin.lift()

    Entry(dbFrame, textvariable=lvar['selLabel'])
    # Text
    elb['display'] = Label(dbFrame, text=lang['display'])
    ent['display'] = Entry(dbFrame, textvariable=lvar['display'], width=22)
    # Font
    elb['font'] = Label(dbFrame, text=lang['fontfamily'])
    ent['font'] =  ttk.Combobox(dbFrame, width=20, state="readonly", values=listFontFamily)
    #Font shape
    elb['fontshape'] = Label(dbFrame, text=lang['fontshape'])
    ent['fontshape'] = ttk.Combobox(dbFrame, width=20, state="readonly", values=listFontShape)
    # Color
    elb['color'] = Label(dbFrame, text=lang['color'])
    ent['color'] = ttk.Combobox(dbFrame, width=20, state="readonly", values=listFontColor)
    # Bg color
    elb['bgcolor'] = Label(dbFrame, text=lang['bgcolor'])
    ent['bgcolor'] = ttk.Combobox(dbFrame, width=20, state="readonly", values=listFontColor)
    # Width
    elb['width'] = Label(dbFrame, text=(lang['width'] + "/" + lang['height']))
    ent['width'] = Entry(dbFrame, textvariable=lvar['width'], width=10)
    ent['height']= Entry(dbFrame, textvariable=lvar['height'], width=10)
    # Padding
    elb['padding'] = Label(dbFrame, text=lang['padding'])
    ent['padX'] = Entry(dbFrame, textvariable=lvar['padX'], width=10)
    ent['padY'] = Entry(dbFrame, textvariable=lvar['padY'], width=10)
    # Device Info
    elb['device_info'] = Label(dbFrame, text=lang['deviceinfo'])
    ent['device_info'] = ttk.Combobox(dbFrame, width=20, state="readonly", values=listDevice)
    # Rule
    elb['rule'] = Label(dbFrame, text=lang['rule'])
    ent['rule'] = Entry(dbFrame, textvariable=lvar['rule'], width=22)
    # Use flag
    elb['use'] = Label(dbFrame, text=lang['use'])
    ent['use'] = Checkbutton(dbFrame, variable=lvar['use'])
    # Pic, Video url
    # elb['url'] = Label(dbFrame, text=lang['url'])
    elb['url'] = Button(dbFrame, command=browseFile, text=lang['url'])
    ent['url'] = Entry(dbFrame, textvariable=lvar['url'], width=22)
    # btnFileBr = Button(dbFrame, command=browseFile, text="select")


    Button(dbFrame, text=lang['save_changes'], command=saveScreen, width=16).grid(row=11, column=0, columnspan=3)

    btFrame = Frame(win)
    btFrame.pack(side="top", pady=10)

    # Button(btFrame, text="\u23F6", command=lambda: movePos('posY', -10), width=4).grid(row=0, column=1, columnspan=2) #^
    # Button(btFrame, text="\u23F7", command=lambda: movePos('posY', 10), width=4).grid(row=2, column=1, columnspan=2) # v
    # Button(btFrame, text="\u23F4", command=lambda: movePos('posX', -10), width=4).grid(row=1, column=0)#<
    # Button(btFrame, text="\u23F5", command=lambda: movePos('posX', 10), width=4).grid(row=1, column=3) #>

    Button(btFrame, text="\u25B2", command=lambda: movePos('posY', -10), width=4).grid(row=0, column=1, columnspan=2) #^
    Button(btFrame, text="\u25BC", command=lambda: movePos('posY', 10), width=4).grid(row=2, column=1, columnspan=2) # v
    Button(btFrame, text="\u25C0", command=lambda: movePos('posX', -10), width=4).grid(row=1, column=0)#<
    Button(btFrame, text="\u25B6", command=lambda: movePos('posX', 10), width=4).grid(row=1, column=3) #>



    btn_f_p = Button(btFrame, text="+", command=fontSizeU, width=2)
    btn_f_m = Button(btFrame, text="-", command=fontSizeD, width=2)

    Label(btFrame, text='X').grid(row=3, column=0)
    Label(btFrame, text='Y').grid(row=3, column=1, columnspan=2)
    Label(btFrame, text='S').grid(row=3, column=3)
    ent['posX'] = Entry(btFrame, textvariable=lvar['posX'], width=4)
    # ent['posX'].grid(row=4, column=0)
    ent['posY'] = Entry(btFrame, textvariable=lvar['posY'], width=4)
    # ent['posY'].grid(row=4, column=1, columnspan=2)
    ent['fontsize'] = Entry(btFrame, textvariable=lvar['fontsize'], width=4)
    # ent['fontsize'].grid(row=4, column=3)
    var['message_str'] = StringVar()
    Message(win, textvariable = var['message_str'], width= 200,  bd=0, relief=SOLID, foreground='red').pack(side="top")

    x = getScreenByName(selLabel)

    ent['posX'].grid(row=4, column=0)
    ent['posY'].grid(row=4, column=1, columnspan=2)
    elb['use'].grid(row=10, column=0, sticky="w", pady=2, padx=4)
    ent['use'].grid(row=10, column=1, sticky="w")

    lvar['width'].set(x.get('size')[0])
    lvar['height'].set(x.get('size')[1])
    lvar['posX'].set(x.get('position')[0])
    lvar['posY'].set(x.get('position')[1])
    lvar['padX'].set(x.get('padding')[0])
    lvar['padY'].set(x.get('padding')[1])

    if x.get('flag') == 'y':
        ent['use'].select()
    else :
        ent['use'].deselect()

    if selLabel.startswith('picture') or selLabel.startswith('snapshot') or selLabel.startswith('video') or selLabel.startswith('mjpeg'):
        elb['width'].grid(row=6, column=0, sticky="w", pady=2, padx=4)
        ent['width'].grid(row=6, column=1, sticky="w", ipadx=3)
        ent['height'].grid(row=6, column=2, sticky="w", ipadx=3)

        if selLabel.startswith('picture') : # image from file
            elb['url'].grid(row=1, column=0, sticky="w", pady=2, padx=4)
            ent['url'].grid(row=1, column=1, columnspan=2, sticky="w")
            lvar['url'].set(x.get('url'))

        else : # image from device
            elb['device_info'].grid(row=1, column=0, sticky="w", pady=2, padx=4)
            ent['device_info'].grid(row=1, column=1, columnspan=2, sticky="w")
            lvar['device_info'].set(x.get('device_info'))
            ent['device_info'].configure(values=listDev)
            for i, ft in enumerate(listDev):
                if x.get('device_info') == ft:
                    ent['device_info'].current(i)
    
    # Text, Number        
    else :
        btn_f_p.grid(row=1, column=1)
        btn_f_m.grid(row=1, column=2)
        ent['fontsize'].grid(row=4, column=3)

        elb['font'].grid(row=2, column=0, sticky="w", pady=2, padx=4)
        ent['font'].grid(row=2, column=1, columnspan=2, sticky="w")
        elb['fontshape'].grid(row=3, column=0, sticky="w", pady=2, padx=4)
        ent['fontshape'].grid(row=3, column=1, columnspan=2, sticky="w")
        elb['color'].grid(row=4, column=0, sticky="w", pady=2, padx=4)
        ent['color'].grid(row=4, column=1, columnspan=2, sticky="w")

        for i, ft in enumerate(listFontFamily):
            if x.get('font')[0] == ft:
                ent['font'].current(i)
        lvar['fontsize'].set(x.get('font')[1])

        for i, ft in enumerate(listFontShape):
            if x.get('font')[2] == ft:
                ent['fontshape'].current(i)

        for i, ft in enumerate(listFontColor):
            if x.get('color')[0] == ft:
                ent['color'].current(i)

        # for i, ft in enumerate(listFontColor):
        #     if x.get('color')[1] == ft:
        #         ent['bgcolor'].current(i)

        if selLabel.startswith('number'):
            elb['device_info'].grid(row=8, column=0, sticky="w", pady=2, padx=4)
            ent['device_info'].grid(row=8, column=1, columnspan=2, sticky="w")
            elb['rule'].grid(row=9, column=0, sticky="w", pady=2, padx=4)
            ent['rule'].grid(row=9, column=1, columnspan=2, sticky="w", ipadx=3)
            ent['device_info'].configure(values=listDevice)
            for i, ft in enumerate(listDevice):
                if x.get('device_info') == ft:
                    ent['device_info'].current(i)                        
            # for i, ft in enumerate(listDevice):
            #     if x.get('device_info') == ft:
            #         ent['device_info'].current(i)

            lvar['rule'].set(x.get('rule'))
        else :
            elb['display'].grid(row=1, column=0, sticky="w", pady=2, padx=4)
            ent['display'].grid(row=1, column=1, columnspan=2, sticky="w", ipadx=3)
            lvar['display'].set(x.get('text'))



#################################################################################################################################################
################################################################# GUI SCREEN  ###################################################################
#################################################################################################################################################

# def forgetLabel(label):
#     global menus
#     menus[label].place_forget()

# def putSectionsx():
#     global ARR_SCREEN, root, var, menus, editmode, bg_canvas
#     # print (root, editmode, selLabel)
#     for rs in ARR_SCREEN:
#         name = rs.get('name')
#         if not (name.startswith('title') or name.startswith('label') or name.startswith('number') or name.startswith('snapshot') or name.startswith('video') or name.startswith('picture')):
#             continue

#         if not name in menus:
#             menus[name] = Label(root)
#             var[name] = StringVar()
#             menus[name].configure(textvariable = var[name])
#             print("create label %s" %name)
#             # menus[name].bind('<Double-Button-1>', lambda event: edit_screen(name))

#         if rs.get('flag') == 'n':
#             menus[name].place_forget()
#             continue
                
#         if rs.get('text'):
#             var[name].set(rs['text'])

#         if rs.get('font'):
#             menus[name].configure(font=tuple(rs['font']))
#         if rs.get('color'):
#             menus[name].configure(fg=rs['color'][0], bg=rs['color'][1])

#         if rs.get('padding'):
#             menus[name].configure(padx=rs['padding'][0], pady=rs['padding'][1])
        
#         w, h = int(rs['size'][0]), int(rs['size'][1]) if rs.get('size') else (0, 0)
#         posx, posy = (int(rs['position'][0]), int(rs['position'][1])) if rs.get('position') else (0, 0)

#         if name.startswith('number'):
#             menus[name].configure(anchor='e')
#         elif name.startswith('picture') :
#             imgPath = rs.get('url')
#             if not (imgPath and os.path.isfile(imgPath)):
#                 imgPath = "cam.jpg"
#             img = cv.imread(imgPath)
#             img = cv.cvtColor(img, cv.COLOR_BGR2RGB)
#             img = Image.fromarray(img)
#             img = img.resize((w, h), Image.LANCZOS)
#             imgtk = ImageTk.PhotoImage(image=img)
#             # menus[name].create_image(0, 0, anchor="nw", image=imgtk)
#             menus[name].configure(image=imgtk)
#             menus[name].photo=imgtk # phtoimage bug
#             # imgPathOld[name] = imgPath

#         elif name.startswith('snapshot'):
#             if rs.get('device_info') :
#                 USE_SNAPSHOT = True
#         elif name.startswith('video'):
#             if rs.get('url') :
#                 USE_VIDEO = True
#         if not editmode:
#               menus[name].configure(borderwidth=0)

#         # if editmode and  selLabel == name:
#         #     menus[name].configure(borderwidth=2, relief="groove")
#         # else :
#         #     menus[name].configure(borderwidth=0)

#         menus[name].configure(width=w, height=h)
#         menus[name].place(x=posx, y=posy)


def putSections():
    global ARR_SCREEN, root, var, menus, bg_canvas, imglist
    # print (root, selLabel)
    def mk_menu(name):
        if name.startswith('title') or name.startswith('label') or name.startswith('number'):
            menus[name] = bg_canvas.create_text(0,0, text="")
           
        else :
            menus[name] = bg_canvas.create_image(0,0,)

    for rs in ARR_SCREEN:
        name = rs.get('name')
        if not (name.startswith('title') or name.startswith('label') or name.startswith('number') or name.startswith('snapshot') or name.startswith('video') or name.startswith('picture')):
            continue

        if not name in menus:
            mk_menu(name)
            print("create cavas_text %s" %name)

        if rs.get('flag') == 'n':
            bg_canvas.delete(menus[name])
            continue
                
        if rs.get('text'):
            bg_canvas.itemconfig(menus[name],text=rs['text'])
            # bg_canvas.itemconfig(menus[name], borderwidth=2, bordercolor="red")


        if rs.get('font'):
            bg_canvas.itemconfig(menus[name],font=tuple(rs['font']))

        if rs.get('color'):
            bg_canvas.itemconfig(menus[name], fill=rs['color'][0])

        posx, posy = (int(rs['position'][0]), int(rs['position'][1])) if rs.get('position') else (0, 0)
        abs_pos = bg_canvas.coords(menus[name])

        if abs_pos:
            posx -= int(abs_pos[0])
            posy -= int(abs_pos[1])
        else:
            mk_menu(name)
            continue

        bg_canvas.move(menus[name], posx, posy)
        if name.startswith('picture'):
            imgPath = rs.get('url')
            w, h = int(rs['size'][0]), int(rs['size'][1]) if rs.get('size') else (100, 100)
            if not (imgPath and os.path.isfile(imgPath)):
                imgPath = "cam.jpg"
            img = cv.imread(imgPath)
            img = cv.cvtColor(img, cv.COLOR_BGR2RGB)
            img = Image.fromarray(img)
            img = img.resize((w,h))
            # Since you use same variable image for all the images created in the for loop, 
            # only the last image is referenced by image and the previous images will be garbage collected.
            # variable must be global not for being garbage collected.
            imglist[name]  = ImageTk.PhotoImage(image=img)
            bg_canvas.itemconfig(menus[name], image=imglist[name] , anchor="center")
            
        # elif name.startswith('video'):
        #     if not name in imglist:
        #         print (rs['url'])
        #         url = "rtsp://192.168.1.28:554/ufirststream"
        #         # imglist[name] = LoadStreams(url)

        # if name.startswith('number'):
        #     menus[name].configure(anchor='e')
        # elif name.startswith('picture') :
        #     imgPath = rs.get('url')
        #     if not (imgPath and os.path.isfile(imgPath)):
        #         imgPath = "cam.jpg"
        #     img = cv.imread(imgPath)
        #     img = cv.cvtColor(img, cv.COLOR_BGR2RGB)
        #     img = Image.fromarray(img)
        #     img = img.resize((w, h), Image.LANCZOS)
        #     imgtk = ImageTk.PhotoImage(image=img)
        #     # menus[name].create_image(0, 0, anchor="nw", image=imgtk)
        #     menus[name].configure(image=imgtk)
        #     menus[name].photo=imgtk # phtoimage bug
        #     # imgPathOld[name] = imgPath

        # elif name.startswith('snapshot'):
        #     if rs.get('device_info') :
        #         USE_SNAPSHOT = True
        # elif name.startswith('video'):
        #     if rs.get('url') :
        #         USE_VIDEO = True
        # if not editmode:
        #       menus[name].configure(borderwidth=0)

        # # if editmode and  selLabel == name:
        # #     menus[name].configure(borderwidth=2, relief="groove")
        # # else :
        # #     menus[name].configure(borderwidth=0)

        # menus[name].configure(width=w, height=h)
        # menus[name].place(x=posx, y=posy)

    # print(imglist)   


        

def changeNumbers(arr):
    global bg_canvas, menus
    for rs in arr:
        # print (rs['name'], rs.get('text'))
        if menus.get(rs['name']):
            bg_canvas.itemconfig(menus[rs['name']], text=rs.get('text'))

def changeSnapshot(cursor):
    global ARR_SCREEN, menus, imglist
    for rs in ARR_SCREEN:
        name = rs.get('name')
        w, h = int(rs['size'][0]), int(rs['size'][1]) if rs.get('size') else (0, 0)
        if name.startswith('snapshot'):
            imgb64 = getSnapshot(cursor, rs.get('device_info'))
            if imgb64:
                imgb64 = imgb64.decode().split("jpg;base64,")[1]
                body = base64.b64decode(imgb64)
                imgarr = np.asarray(bytearray(body), dtype=np.uint8)
                img = cv.imdecode(imgarr, cv.IMREAD_COLOR)

            else :
                img = cv.imread("./cam.jpg")
            
            img = cv.cvtColor(img, cv.COLOR_BGR2RGB)
            img = Image.fromarray(img)
            img = img.resize((w, h), Image.LANCZOS)
            imglist[name]  = ImageTk.PhotoImage(image=img)
            if menus.get(rs['name']):
                bg_canvas.itemconfig(menus[name], image=imglist[name] , anchor="center")
            # menus[name].create_image(0, 0, anchor="nw", image=imgtk)
            # menus[name].configure(image=imgtk)
            # menus[name].photo=imgtk # phtoimage bug
            # imgPathOld[name] = imgPath
        # elif name.startswith('video'):
        #     try :
        #         for frame_idx, img in enumerate(imglist[name]):
        #             cv.imshow('vidwo', img)
        #     except:
        #         imglist[name].stop()

class LoadStreams:  # multiple IP or RTSP cameras
    def __init__(self, s='streams.txt', img_size=640):
        self.img_size = img_size
        self.capture = None
        self.Running = True

        cap = cv.VideoCapture(eval(s) if s.isnumeric() else s)
        assert cap.isOpened(), 'Failed to open %s' % s
        w = int(cap.get(cv.CAP_PROP_FRAME_WIDTH))
        h = int(cap.get(cv.CAP_PROP_FRAME_HEIGHT))
        fps = cap.get(cv.CAP_PROP_FPS) % 100
        _, self.imgs = cap.read()  # guarantee first frame
        thread = threading.Thread(target=self.update, args=(cap,), daemon=True)
        print('success (%gx%g at %.2f FPS).' % (w, h, fps))
        thread.start()
        print('')  # newline
        self.capture = cap

    def update(self, cap):
        while cap.isOpened() and self.Running:
            cap.grab()
            _, self.imgs = cap.retrieve()
            time.sleep(0.01)  # wait time

    def __iter__(self):
        self.count = -1
        return self

    def __next__(self):
        self.count += 1
        # img = self.imgs.copy()
        if cv.waitKey(1) == ord('q'):  # q to quit
            self.capture.release()
            print("raise stopiteration")
            raise StopIteration

        # return img 
        return self.imgs

    def __len__(self):
        return 0  # 1E12 frames = 32 streams at 30 FPS for 30 years

    def stop(self):
        self.Running = False
        print("stop!")

class playVideo(threading.Thread):
    def __init__(self, url):
        threading.Thread.__init__(self)
        self.capture = None
        self.Running = True
        cap = cv.VideoCapture(url)
        assert cap.isOpened(), 'Failed to open %s' %url 
        w = int(cap.get(cv.CAP_PROP_FRAME_WIDTH))
        h = int(cap.get(cv.CAP_PROP_FRAME_HEIGHT))
        fps = cap.get(cv.CAP_PROP_FPS) % 100

        _, self.imgs = cap.read()
        # self.interval = 10 
        # self.label= label_n
        # self.w = 640
        # self.h = 320
        self.capture = cap

    def run(self):
        while self.capture.isOpened():
            _, self.imgs = self.capture.read()

            if cv.waitKey(1) == ord('q'):  # q to quit
                break
            cv.imshow("video", self.imgs)
        
        self.capture.release()
        print("raise stopiteration")
    
    def stop(self):
        self.Running = False
        
        

        
        
        # self.update_image()


    # def update_image(self):    
    #     # Get the latest frame and convert image format
    #     self.OGimage = cv.cvtColor(self.cap.read()[1], cv.COLOR_BGR2RGB) # to RGB
    #     self.OGimage = Image.fromarray(self.OGimage) # to PIL format
    #     self.image = self.OGimage.resize((self.w, self.h), Image.ANTIALIAS)
    #     self.image = ImageTk.PhotoImage(self.image) # to ImageTk format
    #     # Update image
    #     self.label.configure(image=self.image)
    #     # Repeat every 'interval' ms
    #     self.label.after(self.interval, self.update_image)

# class showPicture(threading.Thread):
#     def __init__(self):
#         threading.Thread.__init__(self)
#         self.delay = ARR_CONFIG['refresh_interval']
#         self.Running = True
#         self.exFlag = False
#         self.i = 0

#     def run(self):
#         imgPathOld =  dict()
#         thx = dict()
#         cap=None
#         while self.Running :
#             if self.i == 0:
#                 for rs in ARR_SCREEN:
#                     name  = rs.get('name')
#                     if rs.get('flag')=='n':
#                         continue
#                     if not name in menus:
#                         menus[name] = Label(root, borderwidth=0)
#                         # menus[name] = Canvas(root)

#                     if name.startswith('picture') :
#                         imgPath = rs.get('url')
#                         w, h = rs.get('size')
#                         if not imgPath :
#                             continue
#                         print (imgPath)
#                         img = cv.imread(imgPath)
#                         # img = cv.resize(img, (int(w), int(h)))
#                         img = Image.fromarray(img)
#                         img = img.resize((int(w), int(h)), Image.LANCZOS)
#                         imgtk = ImageTk.PhotoImage(image=img)
#                         # menus[name].create_image(0, 0, anchor="nw", image=imgtk)
#                         menus[name].configure(image=imgtk)
#                         menus[name].photo=imgtk # phtoimage bug
#                         menus[name].configure(width=int(w), height=int(h))
#                         menus[name].place(x=int(rs['position'][0]), y=int(rs['position'][1]))
#                         imgPathOld[name] = imgPath
                    
#                     elif name.startswith('video'):
                       
#                         imgPath = rs.get('url')
#                         w, h = rs.get('size')
#                         if not imgPath:
#                             continue
#                         print (imgPath)
#                         if imgPathOld.get(name) != imgPath:
#                             if cap:
#                                 cap.release()
#                             cap = cv.VideoCapture(imgPath)
#                             thx[name] = playVideo(menus[name], cap)
#                             thx[name].run()
#                             print ("cap init")
#                             imgPathOld[name] = imgPath
#                         menus[name].configure(width=int(w), height=int(h))
#                         thx[name].w = int(w)
#                         thx[name].h = int(h)
#                         menus[name].place(x=int(rs['position'][0]), y=int(rs['position'][1]))
                            
                            
                        
#                         if self.Running == False:
#                             cap.release()
#                             cv.destroyAllWindows()
#                             break
                    
#             self.i += 1
#             if self.i > self.delay:
#                 self.i = 0
#             # print (self.i)
#             time.sleep(1)
#         # if cap:
#         #     cap.release()
#         self.exFlag = True       

#     def stop(self):
#         self.Running = False

class procScreen(threading.Thread):
    def __init__(self):
        threading.Thread.__init__(self)
        self.delay = ARR_CONFIG['refresh_interval']*10
        self.Running = True
        self.exFlag = False
        self.i = 0

    def run(self):
        while self.Running :
            if self.i == 0 :
                # getScreenData()
                   putSections()

            self.i += 1
            if self.i > self.delay:
                self.i = 0
            # print (self.i)
            time.sleep(0.1)
        self.exFlag = True
                
    def stop(self):
        self.Running = False

class getDataThread(threading.Thread):
    def __init__(self):
        threading.Thread.__init__(self)
        self.delay = ARR_CONFIG['refresh_interval']
        self.Running = True
        self.exFlag = False
        self.last = 0
        self.i = 0

    def run(self):
        self.dbcon = dbconMaster()
        while self.Running :
            if self.i == 0 :
                self.cur = self.dbcon.cursor()
                if int(time.time())-self.last > 300:
                # if (int(time.time())%300) < 2: #every 5minute
                    try:
                        updateRptCounting(self.cur)
                        self.last = int(time.time())
                    except Exception as e:
                        print (e)
                        time.sleep(5)
                        self.dbcon = dbconMaster()
                        print ("Reconnected")
                        continue
                
                changeSnapshot(self.cur)
                try :
                    arrn = getNumberData(self.cur)
                    self.dbcon.commit()
                except Exception as e:
                    print (e)
                    time.sleep(5)
                    self.dbcon = dbconMaster()
                    print ("Reconnected")
                    continue

                # print(arrn)
                changeNumbers(arrn)
                self.cur.close()
            
            self.i += 1
            if self.i > self.delay:
                self.i = 0
            # print (self.i)
            time.sleep(1)

        self.cur.close()
        self.dbcon.close()
        self.exFlag = True
                
    def stop(self):
        self.Running = False


def exitProgram(event=None):
    global ths, thd, thv, oWin, root
    print ("Exit Program")
   
    for i in range(100):
        r = True
        s = ""
        if oWin:
            oWin.destroy()

        if ths:
            ths.stop()
            ths.Running = 0
            s += "ths: alive %s, ex %s " %(str(ths.is_alive()), str(ths.exFlag))
            r &= not (ths.is_alive())
            r &= ths.exFlag

        if thd:
            thd.stop()
            thd.Running = 0
            s += "  thd: alive %s, ex %s " %(str(thd.is_alive()), str(thd.exFlag))
            r &= not (thd.is_alive())
            r &= thd.exFlag

        # if thv:
        #     thv.stop()
        #     thv.Running = 0
        #     r &= thv.exFlag

        if i>10:
            sys.stdout.flush()

        
        print (i, r, s)

        if r:
            break
        time.sleep(0.5)

    # root.overrideredirect(False)
    # root.attributes("-fullscreen", False)
    time.sleep(1)
    root.destroy()
    root.quit()
    print ("destroyed root")
    sys.stdout.flush()
    # raise SystemExit()
    # sys.exit()
    # print ("sys.exit()")


class NewCanvas(Canvas):
    def create_border_text(self, x, y, text="Under developing", borderwidth=None, bordercolor=None, **kw):
        textid = self.create_text(x, y, text=text)
        horizontal_offset, vertical_offset = 40, 40
        bbox = self.bbox(textid)
        new_bbox = (bbox[0] - horizontal_offset // 2, bbox[1] - vertical_offset // 2,
                    bbox[2] + horizontal_offset // 2, bbox[3] + vertical_offset // 2)

        self.create_rectangle(*new_bbox, width=borderwidth, outline=bordercolor)
        self.tag_raise(textid)



# def create_rectangle(x1, y1, x2, y2, **kwargs):
#     global imglist
#     if 'alpha' in kwargs:
#         alpha = int(kwargs.pop('alpha') * 255)
#         fill = kwargs.pop('fill')
#         fill = root.winfo_rgb(fill) + (alpha,)
#         image = Image.new('RGBA', (x2-x1, y2-y1), fill)
#         imglist['tr'] = ImageTk.PhotoImage(image)
        
#         # bg_canvas.create_image(x1, y1, image=images[-1], anchor='nw')
#         bg_canvas.create_image(x1, y1, image=imglist['tr'], anchor='nw')
#         # imglist['tr'] = img
#         images.append(imglist['tr'])
#     # bg_canvas.create_rectangle(x1, y1, x2, y2, **kwargs)


if __name__ == '__main__':
    root = Tk()
   
    loadConfig()
    loadTemplate()
    imglist=dict()
    # for m in ARR_SCREEN:
    #     print (m)
    #     if m['name'].startswith('video'):
    #         imglist[m['name']] = LoadStreams(m['url'])
    # root =Tk()
    screen_width = root.winfo_screenwidth()
    screen_height = root.winfo_screenheight()

    root.geometry("%dx%d+0+0" %((screen_width), (screen_height)))

    root.bind('<Double-Button-1>', edit_screen)
    root.bind('<Button-3>', edit_option)
    root.configure(background="black")
    bg_canvas = NewCanvas(root, width=screen_width, height=screen_height, bg="black")
    bg_canvas.place(x=-2, y=-2)



    if ARR_CONFIG.get('background') == 'yes':
        img_path = "bg.jpg"
        if os.path.isfile(img_path):
            var['bg']= StringVar()
            bg_img = cv.imread(img_path)

            if ARR_CONFIG.get('backgroundeffect') == 'bw':
                bg_img = cv.cvtColor(bg_img, cv.COLOR_BGR2GRAY)
            else:
                bg_img = cv.cvtColor(bg_img, cv.COLOR_BGR2RGB)

            bg_img = Image.fromarray(bg_img)
            bg_img = bg_img.resize((screen_width+2, screen_height+2))
            imglist['bg'] = ImageTk.PhotoImage(image=bg_img)
            bg_canvas.create_image(0, 0, image=imglist['bg'], anchor="nw")
            # imglist['bg'] = imgtk
            # bg_canvas.photo=imgtk # phtoimage bug

            # xt = bg_canvas.create_text(500,130, text="HELLO", fill="red", font=('simhei', 20, 'bold'))
            # bg_canvas.itemconfig(xt,text="Hans Kim")
            # bg_canvas.move(xt, 400,200)

    # create_rectangle(80, 80, 150, 120, fill='#800000', alpha=.8)
    ths = procScreen()
    ths.start()

    thd = getDataThread()
    thd.start()
    
    # thv = showPicture()
    # thv.start()

    # cap = cv.VideoCapture(url)
    # d = LoadStreams(url)


    if ARR_CONFIG['full_screen'] == "yes":
        # root.overrideredirect(True)
        root.attributes("-fullscreen", True)
        root.resizable (False, False)
    else :
        root.resizable (True, True)
    # root.wm_attributes('-transparentcolor', 'black')



    root.mainloop()
raise SystemExit()
sys.exit()

