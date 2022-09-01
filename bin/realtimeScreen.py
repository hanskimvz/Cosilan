change_log = """
###############################################################################
rtCount.py
2021-04-16, version 0.9, first

Not valid, goto rtScreen.py
###############################################################################
"""
import time, os, sys
import json
import pymysql
from tkinter import *
import threading
import locale
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
        port = int(MYSQL['port'])

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

def getScreenData(cursor):
    arr = list()
    sq = "select frame, depth, body, flag from cnt_demo.webpage_config where page = 'realtime_screen'"
    cursor.execute(sq)
    for row in cursor.fetchall():
        dct = json.loads(row[2])
        arr.append({
            "name": "%s%d" %(row[0], row[1]),
            "text": dct.get("text"),
            "font": dct["font"],
            "color": dct["color"],
            "size":  dct["size"],
            "position": dct["position"],
            "padding": dct.get("padding"),
            "flag": row[3],
        })
    return arr

def getRtCounting(cursor, rpt_counting):
    ct_mask = list()
    arr_t = list()
    arr_rs = dict()
    arr_rs_y = dict()
    arr_f = set()

    for ct in rpt_counting:
        if ct['day_before'] == 0:
            ct_mask.append("(counter_label='%s' and timestamp>%d)" %(ct['ct_label'], ct['latest']))
            arr_rs[ct['ct_label']] =  {"start":0, "end":0, "rpt_count": ct['ct_value'], "rtcount":0,  "count":0}
        elif ct['day_before'] == 1: 
            arr_rs_y[ct['ct_label']] ={"start":0, "end":0, "rpt_count": ct['ct_value'], "rtcount":0,  "count": ct['ct_value']}

    # print(arr_rs)
    # print(arr_rs_y)
    sq_s = ""
    if (ct_mask) :
        sq_s = ' or '.join(ct_mask)
        sq_s = ' and (%s)' %(sq_s)
    sq = "select timestamp, counter_val, device_info, counter_label, counter_name from common.counting_event where db_name='%s' %s  order by timestamp asc" %(MYSQL['db'], sq_s) 
    # print (sq)
    cursor.execute(sq)
    for row in cursor.fetchall():
        arr_t.append({
            "timestamp": row[0],
            "count": row[1],
            "device_info": row[2],
            "ct_label": row[3],
            "ct_name": row[4]
        })
    
    # print(arr_t)
    for assoc in arr_t:
        label = "%s&%s&%s" %(assoc['device_info'], assoc['ct_label'], assoc['ct_name'])
        if not (label in arr_f) :
            arr_f.add(label)
            arr_rs[assoc['ct_label']]["start"] += assoc['count']
    arr_t.reverse()
    arr_f.clear()
    for assoc in arr_t:
        label = "%s&%s&%s" %(assoc['device_info'], assoc['ct_label'], assoc['ct_name'])
        if not (label in arr_f) :
            arr_f.add(label)
            arr_rs[assoc['ct_label']]["end"] += assoc['count']
    
    for ct_label in arr_rs:
        arr_rs[ct_label]["rtcount"] = arr_rs[ct_label]["end"] - arr_rs[ct_label]["start"]
        arr_rs[ct_label]["count"] = arr_rs[ct_label]["rpt_count"] + arr_rs[ct_label]["rtcount"]

    # print(arr_rs)
    return {'today': arr_rs, 'yesterday': arr_rs_y}

def getRptCounting(cursor, ct_labels):
    arr = list()
    for x in ct_labels:
        arr.append({ "day_before": 0, "ct_label": x, "ct_value": 0, "latest": int(time.time()) + TZ_OFFSET-600, "ref_date": "" })
        arr.append({ "day_before": 1, "ct_label": x, "ct_value": 0, "latest": int(time.time()) + TZ_OFFSET-3600*24, "ref_date": "" })
    # print(arr)
    
    q_labels = " or ".join([ "ct_label = '%s'" %x for x in ct_labels])
    sq = "select category, day_before, ct_label, ct_value, latest, ref_date from %s.realtime_counting where %s" %(MYSQL['db'], q_labels)
    # print (sq)
    cursor.execute(sq)
    for row in cursor.fetchall():
        # print(row)
        for i in range(len(arr)):
            if arr[i]['day_before'] == row[1] and arr[i]['ct_label'] == row[2]:
                arr[i]['ct_value'] = row[3]
                arr[i]['latest']   = row[4]
                arr[i]['ref_date'] = row[5]

    return arr



def getNumberData(cursor):
    arr_number = list()
    ct_labels = set()
    sq = "select frame, depth, body, flag from cnt_demo.webpage_config where page = 'realtime_screen' and frame='number'"
    cursor.execute(sq)
    for row in cursor.fetchall():
        arr = json.loads(row[2])
        if not arr.get("ct_labels"):
            continue
        for ct_label in arr["ct_labels"]:
            ct_labels.add(ct_label)
        arr_number.append({
            "name": "%s%d" %(row[0], row[1]),
            "ct_labels": arr["ct_labels"],
            "rule": arr['rule'],
            "text": 0
        })
    rpt_counting = getRptCounting(cursor, ct_labels)
    print (rpt_counting)
    rt_counting = getRtCounting(cursor, rpt_counting)
    print (rt_counting)
    
    for i, arr in enumerate(arr_number):
        for day in rt_counting:
            if arr['rule'] == day:
                num = 0
                for ct_label in  arr['ct_labels']:
                    num +=  rt_counting[day][ct_label]['count']
                arr_number[i]['text'] = num
            elif arr['rule'].find('-') >0 and day=='today':
                ex = arr['rule'].split('-')
                if len(ex) == 2:
                    num = rt_counting['today'][ex[0]]['count'] - rt_counting['today'][ex[1]]['count']
                    arr_number[i]['text'] = num
            
            elif arr['rule'].find('+') >0 and day=='today':
                ex = arr['rule'].split('+')
                if len(ex) == 2:
                    num = rt_counting['today'][ex[0]]['count'] + rt_counting['today'][ex[1]]['count']
                    arr_number[i]['text'] = num
        if arr['rule'].find('/') >0:
            per_flag = 1 if arr['rule'].lower().find('percent') >=0 else 0 
            num1 = 0
            num2 = 0
            sep = arr['rule'].split(',')  
            for s in sep:
                ex = s.split('/')
                if len(ex) == 2:
                    for ct_label in  arr['ct_labels']:
                        num1 += rt_counting[ex[0].strip()][ct_label]['count'] 
                        num2 += rt_counting[ex[1].strip()][ct_label]['count']
                    num = num1/num2 if num2 else 0
                    arr_number[i]['text'] = "%3.2f %%"  %(num *100) if per_flag else "%3.2f"  %(num)

    for n in arr_number:
        print (n)
    
    return arr_number

def changeNumbers(arr):
    for rs in arr:
        var[rs['name']].set(rs['text'])

class getDataThread(threading.Thread):
    def __init__(self):
        threading.Thread.__init__(self)
        self.delay = refresh_interval
        self.Running = True
        self.exFlag = False

    def run(self):
        self.dbcon = dbconMaster()
        while self.Running :
            self.cur = self.dbcon.cursor()
            try :
                arr = getScreenData(self.cur)
                self.dbcon.commit()
            except pymysql.err.OperationalError as e:
                print (e)
                time.sleep(5)
                self.dbcon = dbconMaster()
                print ("Reconnected")
                continue
            putSections(arr)
            try :
                arr = getNumberData(self.cur)
                self.dbcon.commit()
            except pymysql.err.OperationalError as e:
                print (e)
                time.sleep(5)
                self.dbcon = dbconMaster()
                print ("Reconnected")
                continue
            changeNumbers(arr)

            time.sleep(self.delay)
        self.cur.close()
        self.dbcon.close()
        self.exFlag = True
                
    def stop(self):
        self.Running = False
        # self.cur.close()
        # self.dbcon.close()

def putSections(arr):
    global var, menus
    for rs in arr:
        # print (rs)
        if rs.get('flag') == 'n':
            menus[rs['name']].place_forget()
            continue
        if rs.get('text'):
            var[rs['name']].set(rs['text'])

        menus[rs['name']].configure(font=tuple(rs['font']))
        menus[rs['name']].configure(fg=rs['color'][0], bg=rs['color'][1])
        menus[rs['name']].configure(width=int(rs['size'][0]), height=int(rs['size'][1]))
        if rs.get('padding'):
            menus[rs['name']].configure(padx=rs['padding'][0], pady=rs['padding'][1])
        
        if rs['name'].startswith('number'):
            menus[rs['name']].configure(anchor='e')

        if isinstance(rs['position'][0], int):
            menus[rs['name']].place(x=int(rs['position'][0]))
        elif rs['position'][0] == "center":
            l = 0
            for c in rs['text']:
                if ord(c) >256:
                    l += int(rs['font'][1]*0.8)
                else :
                    l += int(rs['font'][1]*0.4)
            p = int(screen_width / 2) - l
            menus[rs['name']].place(x=p)

        if isinstance(rs['position'][1], int):
            menus[rs['name']].place(y=int(rs['position'][1]))        
    # print()

def exitProgram(event=None):
    global th
    global oWin
    global root
    print ("Exit Program")
    th.stop()
   
    if oWin:
        closeOption()
    
    for i in range(100):
        print (th.exFlag)
        if th.exFlag:
            break
        time.sleep(0.2)

    # root.overrideredirect(False)
    # root.attributes("-fullscreen", False)
    time.sleep(1)
    root.destroy()
    print ("destroyed root")
    raise SystemExit()
    sys.exit()
    print ("sys.exit()")

def closeOption():
    global oWin
    oWin.destroy()
    oWin = None

def loadConfig():
    with open ('rtScreen.json', 'r', encoding='utf8')  as f:
        body = f.read()

    return json.loads(body)


def saveConfig():
    global MYSQL, arrs, var, menus, th, full_screen, refresh_interval
    chMysql = False
    for key in MYSQL:
        if str(MYSQL[key]).strip() != str(var[key].get()).strip():
            print ("%s : %s" %(MYSQL[key], var[key].get()))
            chMysql = True
            break
    
    message ("")
    refresh_interval =  var['refresh_interval'].get()
    try : 
        refresh_interval = int(refresh_interval)
        th.delay = refresh_interval
    except:
        message (lang.get("refresh_time_error"))
        return False

    fx = "yes" if var['full_screen'].get() else "no"
    if full_screen != fx:
        full_screen = fx
        if full_screen == "yes":
            # root.overrideredirect(True)
            root.attributes("-fullscreen", True)
            root.resizable (False, False)
        else :
            root.overrideredirect(False)
            root.attributes("-fullscreen", False)
            root.resizable (True, True)

    
    if chMysql:
        try:
            dbconMaster(
                host = str(var['host'].get().strip()),
                user = str(var['user'].get().strip()), 
                password = str(var['password'].get().strip()),
                charset = str(var['charset'].get().strip()),
                port = int(var['port'].get().strip())
            )

        except Exception as e:
            print ("MYSQL Error")
            print (e)
            message (lang.get("check_mysql_conf"))
            return False


        for key in MYSQL:
            MYSQL[key] = str(var[key].get()).strip()
        
        print (MYSQL)

        th.stop()
        for i in range(50):
            print (th.exFlag)
            if th.exFlag:
                break
            time.sleep(0.2)

        for sect in arrs:
            menus[sect['name']].place_forget()

        dbconn = dbconMaster()
        with dbconn:
            cur = dbconn.cursor()
            arrs = getScreenData(cur)

        for sect in arrs:
            menus[sect['name']] = Label(root)
            var[sect['name']] = StringVar()
            menus[sect['name']].configure(textvariable =var[sect['name']])

        th = getDataThread()
        th.start()

    arr = loadConfig()
    arr['mysql'] = MYSQL
    arr['refresh_interval'] = refresh_interval
    arr['full_screen'] = full_screen
    json_str = json.dumps(arr, ensure_ascii=False, indent=4, sort_keys=True)
    with open("rtScreen.json", "w", encoding="utf-8") as f:
        f.write(json_str)
    message("saved")


def optionMenu(win):
    
    btnFrame = Frame(win)
    btnFrame.pack(side="bottom", pady=10)
    Button(btnFrame, text=lang['close_option'], command=closeOption, width=16).pack(side="left", padx=5)
    Button(btnFrame, text=lang['exit_program'], command=exitProgram, width=16).pack(side="right", padx=5)

    dbFrame = Frame(win)
    dbFrame.pack(side="top", pady=10)

    Label(dbFrame, text=lang['db_server']).grid(row=0, column=0, sticky="w", pady=2, padx=4)
    Label(dbFrame, text=lang['user']).grid(row=1, column=0, sticky="w", pady=2, padx=4)
    Label(dbFrame, text=lang['password']).grid(row=2, column=0, sticky="w", pady=2, padx=4)
    Label(dbFrame, text=lang['charset']).grid(row=3, column=0, sticky="w", pady=2, padx=4)
    Label(dbFrame, text=lang['port']).grid(row=4, column=0, sticky="w", pady=2, padx=4)
    Label(dbFrame, text=lang['db_name']).grid(row=5, column=0, sticky="w", pady=2, padx=4)
    Label(dbFrame, text=lang['refresh_interval']).grid(row=6, column=0, sticky="w", pady=2, padx=4)
    Label(dbFrame, text=lang['full_screen']).grid(row=7, column=0, sticky="w", pady=2, padx=4)

    Entry(dbFrame, textvariable=var['host']).grid(row=0, column=1, ipadx=3)
    Entry(dbFrame, textvariable=var['user']).grid(row=1, column=1, ipadx=3)
    Entry(dbFrame, textvariable=var['password']).grid(row=2, column=1, ipadx=3)
    Entry(dbFrame, textvariable=var['charset']).grid(row=3, column=1, ipadx=3)
    Entry(dbFrame, textvariable=var['port']).grid(row=4, column=1, ipadx=3)
    Entry(dbFrame, textvariable=var['db']).grid(row=5, column=1, ipadx=3)
    Entry(dbFrame, textvariable=var['refresh_interval']).grid(row=6, column=1, ipadx=3)
    cfs = Checkbutton(dbFrame, variable=var['full_screen'])
    cfs.grid(row=7, column=0, columnspan=2)
    Button(dbFrame, text=lang['save_changes'], command=saveConfig, width=16).grid(row=8, column=0, columnspan=2)

    var['refresh_interval'].set(refresh_interval)
    if full_screen == 'yes':
        cfs.select()
    Message(win, textvariable = message_str, width= 300,  bd=0, relief=SOLID, foreground='red').pack(side="top")

def message(strn):
    message_str.set(strn)



def func_mouse(e):
    global oWin
    print(e)
    print(e.x, e.y)

    if oWin: 
        oWin.lift()
    else :
        oWin = Toplevel(root)		
        oWin.title("Device List")
        oWin.geometry("300x400+%d+%d" %(int(screen_width/2-150), int(screen_height/2-200)))
        oWin.protocol("WM_DELETE_WINDOW", closeOption)
        oWin.resizable(True, True)
        oWin.overrideredirect(True)
        optionMenu(oWin)
        

if __name__ == '__main__':
    menus = dict()
    var = dict()
    lang = dict()
    th = None
    oWin = None
    
    LOCALE = locale.getdefaultlocale()
    print (LOCALE)
    if LOCALE[0] == 'zh_CN':
        selected_language = 'Chinese'
    elif LOCALE[0] == 'ko_KR':
        selected_language = 'Korean'
    else :
        selected_language = 'English'

    arr_t = loadConfig()
    for s_lang in arr_t['language']:
        lang[s_lang['key']] = s_lang[selected_language]

    MYSQL = arr_t['mysql']
    print (MYSQL)
    print (lang)
    refresh_interval = arr_t.get("refresh_interval")
    if not refresh_interval:
        refresh_interval = 2

    full_screen = arr_t.get("full_screen")
    if not full_screen:
        full_screen = "no"

    arrs = list()
    try:
        dbconn = dbconMaster()
        with dbconn:
            cur = dbconn.cursor()
            arrs = getScreenData(cur)
    except:
        pass
    # print(arrs)
    # sys.exit()

    root =Tk()
    screen_width = root.winfo_screenwidth()
    screen_height = root.winfo_screenheight()

    root.geometry("%dx%d+0+0" %((screen_width), (screen_height)))

    # root.bind('<Escape>', exitProgram)
    root.bind('<Button-3>', func_mouse)
    root.configure(background="black")

    if full_screen == "yes":
        # root.overrideredirect(True)
        root.attributes("-fullscreen", True)
        root.resizable (False, False)
    else :
        root.resizable (True, True)
    
    var['refresh_interval'] = StringVar()
    var['full_screen'] = IntVar()
    message_str = StringVar()

    for key in MYSQL:
        var[key] = StringVar()
        var[key].set(MYSQL[key])

    for sect in arrs:
        menus[sect['name']] = Label(root)
        var[sect['name']] = StringVar()
        menus[sect['name']].configure(textvariable = var[sect['name']])

    th = getDataThread()
    th.start()
    root.mainloop()

sys.exit()

