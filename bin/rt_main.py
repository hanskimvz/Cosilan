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
import re, json
import pymysql
import locale
import uuid

_ROOT_DIR = os.path.abspath(os.path.dirname(sys.argv[0]))
os.chdir(_ROOT_DIR)

TZ_OFFSET = 3600*8

ARR_CRPT = dict()
ARR_CONFIG = dict()
ARR_SCREEN = list()

lang = dict()

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

def getConfig():
    with open ('%s\\rtScreen.json' %_ROOT_DIR, 'r', encoding='utf8')  as f:
        body = f.read()
    arr = json.loads(body)        

    LOCALE = locale.getdefaultlocale()
    if LOCALE[0] == 'zh_CN':
        selected_language = 'Chinese'
    elif LOCALE[0] == 'ko_KR':
        selected_language = 'Korean'
    else :
        selected_language = 'English'

    for s in arr['language']:
        lang[s['key']] = s[selected_language]

    if not arr['refresh_interval'] :
        arr['refresh_interval'] = 2

    if not arr['full_screen']:
        arr['full_screen'] = "no"
    
    return arr

def writeConfig():
    global ARR_CONFIG
    json_str = json.dumps(ARR_CONFIG, ensure_ascii=False, indent=4, sort_keys=True)
    with open("%s\\rtScreen.json" %_ROOT_DIR, "w", encoding="utf-8") as f:
        f.write(json_str)

def getTemplate(template_doc):
    with open ("%s\\%s" %(_ROOT_DIR, template_doc), 'r', encoding="utf-8") as f:
        body = f.read()
        print ('readed template')
    return json.loads(body)

def writeTemplate():
    global ARR_CONFIG, ARR_SCREEN
    json_str = json.dumps(ARR_SCREEN, ensure_ascii=False, indent=4, sort_keys=True)
    # print(json_str)
    with open("%s\\%s" %(_ROOT_DIR, ARR_CONFIG['template']), "w", encoding="utf-8") as f:
        f.write(json_str)


def getScreenData():
    global ARR_CONFIG, ARR_SCREEN
    with open ("%s\\%s" %(_ROOT_DIR, ARR_CONFIG['template']), 'r', encoding="utf-8") as f:
        body = f.read()
        print ('readed template')
    ARR_SCREEN = json.loads(body)

def writeScreenData():
    global ARR_CONFIG, ARR_SCREEN
    json_str = json.dumps(ARR_SCREEN, ensure_ascii=False, indent=4, sort_keys=True)
    # print(json_str)
    with open("%s\\%s" %(_ROOT_DIR, ARR_CONFIG['template']), "w", encoding="utf-8") as f:
        f.write(json_str)

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
            "ref_date" : 'thisyear',
            "start_ts" : int(time.mktime((tsm.tm_year, 1, 1, 0, 0, 0, 0, 0, 0)) + TZ_OFFSET),
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

            if dt != 'yesterday' :
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



loadConfig()
getScreenData()

def getCRPT():
    return ARR_CRPT
# print (ARR_CONFIG)
# print (ARR_SCREEN)
