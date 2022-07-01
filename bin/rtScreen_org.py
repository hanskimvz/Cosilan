change_log = """
###############################################################################
rtCount.py
2021-04-16, version 0.9, first

###############################################################################
"""
import time, os, sys,random
from tkinter import *

from functions import (CFG, addSlashes, is_online, send_tlss_command, recv_tlss_message, request_cgi, recv_timeout, list_device, dbconMaster, log, info_to_db, parseParam, parseCountReport, parseHeatmapData, parseEventData)
info_to_db('rtCount', change_log)

MYSQL = { 
    "commonParam": CFG('MYSQL', 'DB') + "." + CFG('DB_COMMON', 'PARAM'),
    "commonSnapshot": CFG('MYSQL', 'DB') + "." + CFG('DB_COMMON', 'SNAPSHOT'),
    "commonCounting": CFG('MYSQL', 'DB') + "." + CFG('DB_COMMON','COUNTING'),
    "commonCountEvent": CFG('MYSQL', 'DB') + "." + CFG('DB_COMMON', 'COUNT_EVENT')
}

screenLabel = {'title': "图升电子客流报表"}
def screen(root):
    pad = 3
    label = [None]*12
    number= [None]*12
    def stop_fullscreen(event=None):
        root.overrideredirect(False)
        root.attributes("-fullscreen", False)
        root.destroy()
    
    screen_width = root.winfo_screenwidth()
    screen_height = root.winfo_screenheight()

    screen_center_x = int(screen_width / 2)

    root.geometry("%dx%d+0+0" %((screen_width-pad), (screen_height-pad)))

    root.bind('<Escape>', stop_fullscreen)
    root.configure(background="black")
    root.resizable (False, False)
    root.overrideredirect(True)

    label[0] = Label(root, font=("ds digital", 80, "bold"), fg="white", bg="black" )
    label[0].config(text=screenLabel['title'])
    label[0].pack(side="top", pady=40)

    firstLineFrame = Frame(root, bg="black")
    firstLineFrame.pack(side="top", pady=30)

    label[1] = Label(firstLineFrame, text = "今日进客流", font=("ds digital", 50, "bold"), fg="white", bg="green", width=10)
    label[1].grid(row=0, column=0, padx=10)    

    label[2] = Label(firstLineFrame, text = "", font=("ds digital", 50, "bold"), fg="white", bg="purple", width=10)
    label[2].grid(row=0, column=1, padx=10)    

    label[3] = Label(firstLineFrame, text="限制人数", font=("ds digital", 50, "bold"), fg="white", bg="red", width=10)
    label[3].grid(row=0, column=2, padx=10)    

    label[4] = Label(firstLineFrame, text="累计进人数", font=("ds digital", 50, "bold"), fg="white", bg="green", width=10)
    label[4].grid(row=0, column=3, padx=10)    

    number[1] = Label(firstLineFrame, text="000", font=("ds-digital", 60, 'bold'), bg="black", fg='red')
    number[1].grid(row=1, column=0, sticky=E, ipadx=20)

    number[2] = Label(firstLineFrame, text="000", font=("ds-digital", 60, 'bold'), bg="black", fg='red')
    # number[2].pack(side="left", padx=10)
    number[2].grid(row=1, column=1, sticky=E, ipadx=20)

    number[3] = Label(firstLineFrame, text="0", font=("ds-digital", 60, 'bold'), bg="black", fg='red')
    # number[3].pack(side="left", padx=10)
    number[3].grid(row=1, column=2, sticky=E, ipadx=20)

    number[4] = Label(firstLineFrame, text="0,000,000", font=("ds-digital", 60, 'bold'), bg="black", fg='red')
    # number[4].pack(side="left", padx=10)
    number[4].grid(row=1, column=3, sticky=E, ipadx=20)



    secondLineFrame = Frame(root, bg="black")
    secondLineFrame.pack(side="top", pady=100)
    # secondLineFrame.place(y=screen_height/2)

    label[5] = Label(secondLineFrame, text = "营业时间", font=("ds digital", 50, "bold"), fg="white", bg="blue", width=12)
    # label[1].pack(side="left", padx=10)
    label[5].grid(row=0, column=0, padx=10)    

    # label[6] = Label(secondLineFrame, text = "OCCUPY", font=("ds digital", 50, "bold"), fg="white", bg="blue", width=13)
    # # label[2].pack(side="left", padx=10)
    # label[6].grid(row=0, column=1, padx=10)    

    label[7] = Label(secondLineFrame, text="服务电话", font=("ds digital", 50, "bold"), fg="white", bg="blue", width=12)
    # label[3].pack(side="left", padx=10)
    label[7].grid(row=0, column=2, padx=10)    

    number[5] = Label(secondLineFrame, text="08:30-22:00",  font=("ds-digital", 60, 'bold'), bg="black", fg='red')
    # number[1].pack(side="left", padx=10)
    number[5].grid(row=1, column=0, padx=10)

    number[6] = Label(secondLineFrame, text="2021-01-21\n21:00 星期二",  font=("ds-digital", 60, 'bold'), bg="black", fg='red', width=18)
    # number[2].pack(side="left", padx=10)
    number[6].grid(row=0, column=1, rowspan=2, padx=10)

    number[7] = Label(secondLineFrame, text="010-2760-1152",  font=("ds-digital", 60, 'bold'), bg="black", fg='red')
    # number[2].pack(side="left", padx=10)
    number[7].grid(row=1, column=2, padx=10)
    
   
    return label, number

if __name__ == '__main__':
    root =Tk()
    label, number = screen(root)


    root.mainloop()

exit()



import multiprocessing, threading
import time, os, sys,random
from tkinter import *
from configparser import ConfigParser

from http.client import HTTPConnection
from  urllib.parse import urlparse, parse_qsl, unquote

import socket
import re, base64, struct
import pymysql


config_file = "config.ini"
if not os.path.isfile(config_file):
    print ("No Config File")
    exit()

cfg =  ConfigParser()
cfg.read(config_file, encoding='utf-8')

def CFG(SECTION, OPTION='') :
    cfg_ = None
    try:
        cfg_ = cfg.get(SECTION, OPTION)
    except Exception as e:
        pass
    return cfg_

def dbconMaster(): #Mysql
    dbconn0 = pymysql.connect(host = str(''), user = str(CFG('MYSQL','USER')), password = str(CFG('MYSQL', 'PASSWORD')), db = str(CFG('MYSQL', 'DB')), charset = str(CFG('MYSQL', 'CHARSET')))
    return dbconn0

def addSlashes(strings):
	if isinstance(strings, bytes):
		try:
			strings = strings.decode("utf-8")
		except :
			strings = strings.decode("utf-16")
	symbols = ["\\", '"', "'", "\0", ]
	for i in symbols:
		if i in strings: 
			strings = strings.replace(i, '\\' + i)
	return strings

def recv_timeout(conn,timeout=2):
    conn.setblocking(0)
    total_data=[]
    data=''
    begin=time.time()
    while 1:
        if total_data and time.time()-begin > timeout:
            break
         
        elif time.time()-begin > timeout*2:
            break
         
        try:
            data = conn.recv(1024)
            if data:
                total_data.append(data)
                begin=time.time()
            else:
                time.sleep(0.1)
        except:
            pass
    return  b''.join(total_data)


def parse_eventdata(data, tunnel="HTTP"):
    rs = dict()
    info = list()

    """
    DOOFTEN305EVENT/1.0
    ip=192.168.1.28
    unitname=NS1402HD-6117
    datetime=Fri Jan 15 05:11:59 2021
    dts=1610658719.939152
    type=vca
    info=ch=0&type=counting&ct1[id=1,name=Counter 1,val=4014813]&timestamp=1610658719.939152
    id=76071E71-161E-4B87-9C78-923DDE2F61C4
    rulesname=counter
    rulesdts=1610658719.976864
    """
    """
    GET /count?ip%3d192.168.1.28%26unitname%3dNS1402HD-6117%26datetime%3dTue%20Jan%2019%2021:29:53%202021%26dts%3d1611062993.685058%26type%3dvca%26info%3dch%3d0%26type%3dcounting%26ct1%5bid%3d1%2cname%3dCounter%201%2cval%3d4106028%5d%26timestamp%3d1611062993.685058%26id%3d35248F55-71C1-4D82-9DB0-838ABAD4B62D%26rulesname%3dcounter%26rulesdts%3d1611062993.734832%26usn%3dG90A0031C HTTP/1.1
    Host: 192.168.1.2:5300
    Accept: */*
    """
    if tunnel == "HTTP":
        data = unquote(data.decode('ascii'))
        data =  data.strip()
        string = data.split("HTTP/1.1")[0].strip()
        regex_info = re.compile(r"\&info=ch=0\&type=counting\&ct(\d+)\[id=(\d+),name=(.+),val=(\d+)\]\&timestamp=(\d+).[0-9]*\&", re.IGNORECASE)
        m_info = regex_info.finditer(string)
        for m in m_info:
            info.append({'ct_id':m.group(2), 'ct_name': m.group(3), 'ct_val':m.group(4), 'timestamp':m.group(5)})
            string = string.replace(m.group(), "&")

        rs = dict(parse_qsl(urlparse(string).query))
        # rs['ip'] = parced_field['ip']
        # rs['unitname'] = parced_field['unitname']
        # rs['datetime'] = parced_field['datetime']
        # rs['usn'] = parced_field['usn']
        rs['info'] = info
        rs['message'] = data.replace("\n", " ")

    return rs

def event_counting_thread(conn):
    data = recv_timeout(conn) #<class 'byte'>
    # if data[0:20].find(b"DOOFTEN") <0:
    #     log.error("Invalid Event Data:%s...." %data[0:300])
    #     conn.close()
    #     return False

    rs = parse_eventdata(data)
    # print (rs)

    dbconn0 = dbconMaster()
    cur = dbconn0.cursor()
    # """
    # +--------------+------------------+------+-----+---------+----------------+
    # | Field        | Type             | Null | Key | Default | Extra          |
    # +--------------+------------------+------+-----+---------+----------------+
    # | pk           | int(11) unsigned | NO   | PRI | NULL    | auto_increment |
    # | device_ip    | varchar(255)     | YES  |     | NULL    |                |
    # | device_info  | varchar(255)     | YES  | MUL | NULL    |                |
    # | regdate      | datetime         | YES  |     | NULL    |                |
    # | timestamp    | int(10) unsigned | YES  |     | NULL    |                |
    # | counter_name | varchar(255)     | YES  |     | NULL    |                |
    # | counter_val  | int(11) unsigned | YES  |     | NULL    |                |
    # | message      | text             | YES  |     | NULL    |                |
    # | flag         | enum('y','n')    | YES  |     | n       |                |
    # | status       | int(2) unsigned  | YES  |     | 0       |                |
    # +--------------+------------------+------+-----+---------+----------------+
    # """

    rs['deviceinfo'] = rs['usn'] + "&" + rs['unitname']
    # i= 0 
    for i in range(0, len(rs['info'])):
        sq = "select counter_val from " + CFG('MYSQL', 'DB') + "." + CFG('DB_COMMON', 'COUNT_EVENT') + " where device_info='%s' and counter_name='%s' order by timestamp desc limit 1 " %(rs['deviceinfo'], rs['info'][i]['ct_name'])
        cur.execute(sq)
        row = cur.fetchone()
        print (row)
        
        counter_diff = int(rs['info'][i]['ct_val']) - int(row[0]) if row else 0
        if counter_diff < 0 :
            counter_diff = 0

        sq = "insert into " + CFG('MYSQL', 'DB') + "." + CFG('DB_COMMON', 'COUNT_EVENT') + "(device_ip, device_info, regdate, timestamp, counter_name, counter_val, counter_diff, message, flag, status)  values('%s', '%s', '%s', %d, '%s', %d, %d, '%s', 'y', 0) " %(rs['ip'], rs['deviceinfo'], time.strftime("%Y-%m-%d %H:%M:%S"), int(rs['info'][i]['timestamp']), rs['info'][i]['ct_name'], int(rs['info'][i]['ct_val']), int(counter_diff), addSlashes(rs['message']) )

        print (sq)
        cur.execute(sq)
    dbconn0.commit()

    dt = time.localtime()

    hour, minute = CFG('SET', 'RESET_TIME').split(":")
    timestamp =  time.mktime((dt.tm_year, dt.tm_mon, dt.tm_mday, int(hour), int(minute), dt.tm_sec, dt.tm_wday, dt.tm_yday, dt.tm_isdst))
    # timestamp = 0
    sq = "select sum(counter_diff) from " + CFG('MYSQL', 'DB') + "." + CFG('DB_COMMON', 'COUNT_EVENT') + " where timestamp >" + str(int(timestamp)) + " and counter_name='" + CFG('COUNTER', 'IN') +"' "
    print (sq)
    cur.execute(sq)
    row = cur.fetchone()
    if row and row[0] != None:
        print (row)
        v_in = int(row[0])
        try:
            number[1].config(text ="{:,d}".format(int(v_in)))
        except RuntimeError as e:
            print (e)
            sys.exit()


    sq = "select sum(counter_diff) from " + CFG('MYSQL', 'DB') + "." + CFG('DB_COMMON', 'COUNT_EVENT') + " where timestamp >" + str(int(timestamp)) + " and counter_name='" + CFG('COUNTER', 'OUT') +"' "
    cur.execute(sq)
    row = cur.fetchone()
    if row and row[0] != None:
        v_out = int(row[0])
        number[2].config(text ="{:,d}".format(v_in - v_out))

    sq = "select sum(counter_diff) from " + CFG('MYSQL', 'DB') + "." + CFG('DB_COMMON', 'COUNT_EVENT') + " where counter_name='" + CFG('COUNTER', 'IN') +"' "
    cur.execute(sq)
    row = cur.fetchone()
    if row and row[0] != None:
        number[4].config(text ="{:,d}".format(int(row[0])))



    cur.close()
    dbconn0.close()
    conn.close()
    print ("updated")

def eventThread():
    try:
        s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    except socket.error as  msg:
        print("Could not create socket. Error Code: {0}, Error: {1}".format(str(msg[0], msg[1])))
        sys.exit(0)
    print("[-] Socket Created(Count Event)")

    # bind socket
    try:
        s.bind(('', int(CFG('PORT', 'COUNT_EVENT'))))
        print("[-] Socket Bound to port {0}".format(str(CFG('PORT', 'COUNT_EVENT'))))

    except socket.error as msg:
        print("Bind Failed. Error: {0}".format(str(msg)))
        print ("Bind Failed. Error: {0}".format(str(msg)))
        s.close()
        sys.exit()

    s.listen(10)
    print("count Event Engine: Listening...%d" %(int(CFG('PORT', 'COUNT_EVENT'))))

    while True :
        conn, addr = s.accept()
        print("CountEvent: %s:%s" %(addr[0], str(addr[1])) + " connected")
        t0 = threading.Thread(target=event_counting_thread, args=(conn, ))
        t0.start()
    s.close()

def screen(root):
    pad = 3
    label = [None]*12
    number= [None]*12
    def stop_fullscreen(event=None):
        root.overrideredirect(False)
        root.attributes("-fullscreen", False)
    
    screen_width = root.winfo_screenwidth()
    screen_height = root.winfo_screenheight()

    screen_center_x = int(screen_width / 2)

    root.geometry("%dx%d+0+0" %((screen_width-pad), (screen_height-pad)))

    root.bind('<Escape>', stop_fullscreen)
    # root.protocol("WM_DELETE_WINDOW", sys.exit())
    root.configure(background="black")
    root.resizable (False, False)
    
    root.overrideredirect(True)

    label[0] = Label(root, font=("ds digital", 80, "bold"), fg="white", bg="black" )
    text = "图升电子客流报表"
    label[0].config(text =text)
    # label[0].place(x = (screen_center_x - 400), y= 20)
    label[0].pack(side="top", pady=40)

    firstLineFrame = Frame(root, bg="black")
    firstLineFrame.pack(side="top", pady=30)

    label[1] = Label(firstLineFrame, text = "今日进客流", font=("ds digital", 50, "bold"), fg="white", bg="green", width=10)
    # label[1].pack(side="left", padx=10)
    label[1].grid(row=0, column=0, padx=10)    

    label[2] = Label(firstLineFrame, text = "", font=("ds digital", 50, "bold"), fg="white", bg="purple", width=10)
    # label[2].pack(side="left", padx=10)
    label[2].grid(row=0, column=1, padx=10)    

    label[3] = Label(firstLineFrame, text="限制人数", font=("ds digital", 50, "bold"), fg="white", bg="red", width=10)
    # label[3].pack(side="left", padx=10)
    label[3].grid(row=0, column=2, padx=10)    

    label[4] = Label(firstLineFrame, text="累计进人数", font=("ds digital", 50, "bold"), fg="white", bg="green", width=10)
    # label[4].pack(side="left", padx=10)
    label[4].grid(row=0, column=3, padx=10)    

    number[1] = Label(firstLineFrame, text="000", font=("ds-digital", 60, 'bold'), bg="black", fg='red')
    # number[1].pack(side="left", padx=10)
    number[1].grid(row=1, column=0, sticky=E, ipadx=20)

    number[2] = Label(firstLineFrame, text="000", font=("ds-digital", 60, 'bold'), bg="black", fg='red')
    # number[2].pack(side="left", padx=10)
    number[2].grid(row=1, column=1, sticky=E, ipadx=20)

    number[3] = Label(firstLineFrame, text="0", font=("ds-digital", 60, 'bold'), bg="black", fg='red')
    # number[3].pack(side="left", padx=10)
    number[3].grid(row=1, column=2, sticky=E, ipadx=20)

    number[4] = Label(firstLineFrame, text="0,000,000", font=("ds-digital", 60, 'bold'), bg="black", fg='red')
    # number[4].pack(side="left", padx=10)
    number[4].grid(row=1, column=3, sticky=E, ipadx=20)



    secondLineFrame = Frame(root, bg="black")
    secondLineFrame.pack(side="top", pady=100)
    # secondLineFrame.place(y=screen_height/2)

    label[5] = Label(secondLineFrame, text = "营业时间", font=("ds digital", 50, "bold"), fg="white", bg="blue", width=12)
    # label[1].pack(side="left", padx=10)
    label[5].grid(row=0, column=0, padx=10)    

    # label[6] = Label(secondLineFrame, text = "OCCUPY", font=("ds digital", 50, "bold"), fg="white", bg="blue", width=13)
    # # label[2].pack(side="left", padx=10)
    # label[6].grid(row=0, column=1, padx=10)    

    label[7] = Label(secondLineFrame, text="服务电话", font=("ds digital", 50, "bold"), fg="white", bg="blue", width=12)
    # label[3].pack(side="left", padx=10)
    label[7].grid(row=0, column=2, padx=10)    

    number[5] = Label(secondLineFrame, text="08:30-22:00",  font=("ds-digital", 60, 'bold'), bg="black", fg='red')
    # number[1].pack(side="left", padx=10)
    number[5].grid(row=1, column=0, padx=10)

    number[6] = Label(secondLineFrame, text="2021-01-21\n21:00 星期二",  font=("ds-digital", 60, 'bold'), bg="black", fg='red', width=18)
    # number[2].pack(side="left", padx=10)
    number[6].grid(row=0, column=1, rowspan=2, padx=10)

    number[7] = Label(secondLineFrame, text="010-2760-1152",  font=("ds-digital", 60, 'bold'), bg="black", fg='red')
    # number[2].pack(side="left", padx=10)
    number[7].grid(row=1, column=2, padx=10)
    
   
    return label, number

def changeTxt(lbl, value=''):
    # for i in range (10):
    #     lbl.config(text=str(i))
    #     lbl.refresh()
    #     time.sleep(1)
    print (lbl)
    lbl.config(text=value)

def start():
    dow = ["星期日","星期一","星期二","星期三","星期四","星期五","星期六"]
    w = dow[int(time.strftime("%w"))]
    text = time.strftime("%Y-%m-%d\n%H:%M:%S") + " " + w
    number[6].config(text=text)
    number[6].after(200, start)

    # up = random.randint(0,9)
    # text =  "{:,d}".format(up)
    # number[1].config(text=text)
    
    

if __name__ == '__main__':
    # hour, minute = CFG('SET', 'RESET_TIME').split(":")
    # print (int(hour))
    # print (int(minute))
    # exit()

    # dt = time.localtime()

    # hour, minute = CFG('SET', 'RESET_TIME').split(":")
    # timestamp =  time.mktime((dt.tm_year, dt.tm_mon, dt.tm_mday, int(hour), int(minute), 0, dt.tm_wday, dt.tm_yday, dt.tm_isdst))

    # print (timestamp)
    # exit()    

    th = threading.Thread(target=eventThread, args=[])
    th.start()
    
    root =Tk()
    label, number = screen(root)
    
    for i in range(len(cfg.options("LABEL"))) :
        if CFG("LABEL", "LABEL%d" %i) : 
            label[i].config(text= CFG("LABEL", "LABEL%d" %i))

    number[5].config(text =CFG("VALUE", "OPEN_HOUR"))
    number[7].config(text =CFG("VALUE", "AS_TEL"))
    number[3].config(text ="{:,d}".format(int(CFG("VALUE", "LIMIT_PEOPLE"))))

    print (CFG("COUNTER", "IN"))
    # print (number[1])
    # number[1].config(text="56789")
    # number[1].config(text="12345")
    start()

    # output_p, input_p = multiprocessing.Pipe()

    # cons_p = multiprocessing.Process(target=consumer, args=((output_p, input_p),))

    # cons_p.start()

    # output_p.close()

    # sequence = [1,2,3,4]
    # producer(sequence, input_p)

    # input_p.close()

    # cons_p.join()
    # ct1 = Label(root, font=("ds-digital", 30, 'bold'), bg="black", fg='light blue', bd=50)
    # ct1.grid(row=2,column=2)
    # for i in range(100):
    #     label =ct1
    #     strNumber = random.randint(0,99)
    #     changeNumber(label, strNumber)
    #     time.sleep(1)
    root.mainloop()
