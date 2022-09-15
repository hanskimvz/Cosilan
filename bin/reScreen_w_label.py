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
import shutil
import json, base64
from tkinter import *
from tkinter import ttk
from tkinter import filedialog
import cv2 as cv
import numpy as np
from PIL import ImageTk, Image
import threading

from rt_main import lang, _ROOT_DIR, ARR_SCREEN, ARR_CONFIG, ARR_CRPT, dbconMaster, getScreenData, writeScreenData, parseRule, updateRptCounting, getCRPT, getNumberData, getSnapshot

oWin = None
eWin = None
ths = None
thd = None
thv = None

menus = dict()
var = dict()
editmode = False
selLabel = None

def exitProgram(event=None):
    global ths, thd, thv, oWin, eWin, root
    print ("Exit Program")
   
    for i in range(100):
        r = True
        s = ""
        if oWin:
            closeOption()
        if eWin:
            closeEdit()

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


#################################################################################################
######################### GUI ###################################################################
#################################################################################################
def forgetLabel(label):
    global menus
    menus[label].place_forget()

def putSections():
    global ARR_SCREEN, root, var, menus, editmode
    # print (root, editmode, selLabel)
    for rs in ARR_SCREEN:
        name = rs.get('name')
        if not (name.startswith('title') or name.startswith('label') or name.startswith('number') or name.startswith('snapshot') or name.startswith('video') or name.startswith('picture')):
            continue

        if not name in menus:
            menus[name] = Label(root)
            var[name] = StringVar()
            menus[name].configure(textvariable = var[name])
            print("create label %s" %name)
            # menus[name].bind('<Double-Button-1>', lambda event: edit_screen(name))

        if rs.get('flag') == 'n':
            menus[name].place_forget()
            continue
                
        if rs.get('text'):
            var[name].set(rs['text'])

        if rs.get('font'):
            menus[name].configure(font=tuple(rs['font']))
        if rs.get('color'):
            menus[name].configure(fg=rs['color'][0], bg=rs['color'][1])

        if rs.get('padding'):
            menus[name].configure(padx=rs['padding'][0], pady=rs['padding'][1])
        
        w, h = int(rs['size'][0]), int(rs['size'][1]) if rs.get('size') else (0, 0)
        posx, posy = (int(rs['position'][0]), int(rs['position'][1])) if rs.get('position') else (0, 0)

        if name.startswith('number'):
            menus[name].configure(anchor='e')
        elif name.startswith('picture') :
            imgPath = rs.get('url')
            if not (imgPath and os.path.isfile(imgPath)):
                imgPath = "cam.jpg"
            img = cv.imread(imgPath)
            img = cv.cvtColor(img, cv.COLOR_BGR2RGB)
            img = Image.fromarray(img)
            img = img.resize((w, h), Image.LANCZOS)
            imgtk = ImageTk.PhotoImage(image=img)
            # menus[name].create_image(0, 0, anchor="nw", image=imgtk)
            menus[name].configure(image=imgtk)
            menus[name].photo=imgtk # phtoimage bug
            # imgPathOld[name] = imgPath

        elif name.startswith('snapshot'):
            if rs.get('device_info') :
                USE_SNAPSHOT = True
        elif name.startswith('video'):
            if rs.get('url') :
                USE_VIDEO = True
        if not editmode:
              menus[name].configure(borderwidth=0)

        # if editmode and  selLabel == name:
        #     menus[name].configure(borderwidth=2, relief="groove")
        # else :
        #     menus[name].configure(borderwidth=0)

        menus[name].configure(width=w, height=h)
        menus[name].place(x=posx, y=posy)



def changeNumbers(arr):
    for rs in arr:
        if var.get(rs['name']):
            var[rs['name']].set(rs.get('text'))


def changeSnapshot(cursor):
    global ARR_SCREEN, menus
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
            imgtk = ImageTk.PhotoImage(image=img)
            # menus[name].create_image(0, 0, anchor="nw", image=imgtk)
            menus[name].configure(image=imgtk)
            menus[name].photo=imgtk # phtoimage bug
            # imgPathOld[name] = imgPath



class playVideo():
    def __init__(self, label_n, cap):
        self.cap = cap
        self.interval = 10 
        self.label= label_n
        self.w = 640
        self.h = 320
    def run(self):
        self.update_image()

    def update_image(self):    
        # Get the latest frame and convert image format
        self.OGimage = cv.cvtColor(self.cap.read()[1], cv.COLOR_BGR2RGB) # to RGB
        self.OGimage = Image.fromarray(self.OGimage) # to PIL format
        self.image = self.OGimage.resize((self.w, self.h), Image.ANTIALIAS)
        self.image = ImageTk.PhotoImage(self.image) # to ImageTk format
        # Update image
        self.label.configure(image=self.image)
        # Repeat every 'interval' ms
        self.label.after(self.interval, self.update_image)

class showPicture(threading.Thread):
    def __init__(self):
        threading.Thread.__init__(self)
        self.delay = ARR_CONFIG['refresh_interval']
        self.Running = True
        self.exFlag = False
        self.i = 0

    def run(self):
        imgPathOld =  dict()
        thx = dict()
        cap=None
        while self.Running :
            if self.i == 0:
                for rs in ARR_SCREEN:
                    name  = rs.get('name')
                    if rs.get('flag')=='n':
                        continue
                    if not name in menus:
                        menus[name] = Label(root, borderwidth=0)
                        # menus[name] = Canvas(root)

                    if name.startswith('picture') :
                        imgPath = rs.get('url')
                        w, h = rs.get('size')
                        if not imgPath :
                            continue
                        print (imgPath)
                        img = cv.imread(imgPath)
                        # img = cv.resize(img, (int(w), int(h)))
                        img = Image.fromarray(img)
                        img = img.resize((int(w), int(h)), Image.LANCZOS)
                        imgtk = ImageTk.PhotoImage(image=img)
                        # menus[name].create_image(0, 0, anchor="nw", image=imgtk)
                        menus[name].configure(image=imgtk)
                        menus[name].photo=imgtk # phtoimage bug
                        menus[name].configure(width=int(w), height=int(h))
                        menus[name].place(x=int(rs['position'][0]), y=int(rs['position'][1]))
                        imgPathOld[name] = imgPath
                    
                    elif name.startswith('video'):
                       
                        imgPath = rs.get('url')
                        w, h = rs.get('size')
                        if not imgPath:
                            continue
                        print (imgPath)
                        if imgPathOld.get(name) != imgPath:
                            if cap:
                                cap.release()
                            cap = cv.VideoCapture(imgPath)
                            thx[name] = playVideo(menus[name], cap)
                            thx[name].run()
                            print ("cap init")
                            imgPathOld[name] = imgPath
                        menus[name].configure(width=int(w), height=int(h))
                        thx[name].w = int(w)
                        thx[name].h = int(h)
                        menus[name].place(x=int(rs['position'][0]), y=int(rs['position'][1]))
                            
                            
                        
                        if self.Running == False:
                            cap.release()
                            cv.destroyAllWindows()
                            break
                    
            self.i += 1
            if self.i > self.delay:
                self.i = 0
            # print (self.i)
            time.sleep(1)
        # if cap:
        #     cap.release()
        self.exFlag = True       

    def stop(self):
        self.Running = False

class procScreen(threading.Thread):
    def __init__(self):
        threading.Thread.__init__(self)
        self.delay = ARR_CONFIG['refresh_interval']
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
            time.sleep(1)
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

#########################################################################################################
############################################## Option Config ############################################
#########################################################################################################
def frame_option(e=None):
    global oWin, var, ARR_CONFIG
    # print(e)
    var['background'] = IntVar()
    var['refresh_interval'] = StringVar()
    var['full_screen'] = IntVar()
    var['template'] = StringVar()
    var['message_str'] = StringVar()
    

    for key in ARR_CONFIG['mysql']:
        var[key] = StringVar()
        var[key].set(ARR_CONFIG['mysql'][key])

    if oWin: 
        oWin.lift()
    else :
        oWin = Toplevel(root)		
        oWin.title("Configuration")
        oWin.geometry("300x400+%d+%d" %(int(screen_width/2-150), int(screen_height/2-200)))
        oWin.protocol("WM_DELETE_WINDOW", closeOption)
        oWin.resizable(False, False)
        # oWin.overrideredirect(True)
        optionMenu(oWin)
    ths.delay =  ARR_CONFIG['refresh_interval']

def closeOption():
    global oWin
    oWin.destroy()
    oWin = None


def optionMenu(win):
    global ARR_CONFIG, var
    # print (sys.executable)
    def saveConfig():
        global ARR_CONFIG, ARR_SCREEN, var, thd, ths, menus
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

        # if ARR_CONFIG['template'] != var['template'].get().strip():
        if ARR_CONFIG['template'] != template.get().strip():
            # ARR_CONFIG['template'] = var['template'].get().strip()
            ARR_CONFIG['template'] = template.get().strip()
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

        json_str = json.dumps(ARR_CONFIG, ensure_ascii=False, indent=4, sort_keys=True)
        with open("%s\\rtScreen.json" %_ROOT_DIR, "w", encoding="utf-8") as f:
            f.write(json_str)
        message("saved")
        if need_restart:
            #restart
            sys.stdout.flush()
            os.execv(sys.executable, ["python3.exe"] + sys.argv)
            # os.execv("python3.exe", sys.argv)

    def saveBG():
        global oWin, var
        fname = filedialog.askopenfilename(title="Select imagefile", filetypes=[("image", ".jpeg"),("image", ".png"),("image", ".jpg"),])
        print(fname)
        var['bgTemp'] = fname
        oWin.lift()

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
    # Entry(dbFrame, textvariable=var['background']).grid(row=6, column=1, ipadx=3)
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
    template = ttk.Combobox(dbFrame, width=16, values=listTemplates)
    template.grid(row=9, column=1, ipadx=3)
    Button(dbFrame, text=lang['save_changes'], command=saveConfig, width=16).grid(row=10, column=0, columnspan=2)

    var['refresh_interval'].set(ARR_CONFIG['refresh_interval'])
    for i, x in enumerate(listTemplates):
        if x == ARR_CONFIG['template']:
            template.current(i)


    Message(win, textvariable = var['message_str'], width= 300,  bd=0, relief=SOLID, foreground='red').pack(side="top")

def message(strn):
    var['message_str'].set(strn)




#########################################################################################################
############################################## Screen Edit ##############################################
#########################################################################################################
def edit_screen(e):
    global ARR_SCREEN, menus, eWin, editmode, selLabel
    # print(e.widget)
    if str(e.widget) != '.':
        for m in menus:
            # print (m, e.widget._name, menus[m]._name)
            if str(e.widget._name) == str(menus[m]._name):
                selLabel = m
                break
    ths.delay = 1
    editmode = True
    print(selLabel)
    if selLabel:
        for m in menus:
            menus[m].configure(borderwidth=0, relief="groove")
        menus[selLabel].configure(borderwidth=2, relief="groove")

    if eWin: 
        # eWin.lift()
        eWin.destroy()
    # else :
    eWin = Toplevel(root)		
    eWin.title("Edit Screen")
    eWin.geometry("260x600+%d+%d" %(int(screen_width/2-150), int(screen_height/2-200)))
    eWin.protocol("WM_DELETE_WINDOW", closeEdit)
    eWin.resizable(True, True)
    editScreen(eWin)
    
def closeEdit():
    global eWin, editmode, selLabel
    eWin.destroy()
    eWin = None
    editmode = False
    selLabel= None
    ths.delay = ARR_CONFIG['refresh_interval']

def editScreen(win):
    global ARR_SCREEN, ARR_CRPT, menus, selLabel
    btnFrame = Frame(win)
    btnFrame.pack(side="bottom", pady=10)
    Button(btnFrame, text=lang['close_option'], command=closeEdit, width=16).pack(side="left", padx=5)

    dbFrame = Frame(win)
    dbFrame.pack(side="top", pady=10)

    listLabels = list()
    listDev = set()
    listDevice = list()
    listFontFamily = ['simhei', 'arial', 'fangsong', 'simsun', 'gulim', 'batang', 'ds-digital','bauhaus 93', 'HP Simplified' ]
    listFontShape  = ['normal', 'bold', 'italic']
    listFontColor  = ['white', 'black', 'orange', 'blue', 'red', 'green', 'purple', 'grey', 'yellow', 'pink']

    for x in ARR_SCREEN:
        listLabels.append(x['name'])

    arr = getCRPT()
    for dt in arr:
        for x in arr[dt]:
            if x == 'all':
                continue
            listDev.add(x)
    listDevice = list(listDev)
    listDev = list(listDev)
    listDevice.insert(0, "all") # include all
    

    arr_lvar = ['display', 'font', 'fontsize', 'fontshape', 'color', 'bgcolor', 'width', 'height', 'posX', 'posY', 'padX', 'padY', 'device_info', 'rule','use', 'url']
    lvar = dict()
    elb = dict()
    ent = dict()

    for x in arr_lvar:
        lvar[x] = StringVar()
    
    def showEntry():
        i=0
        for x in ARR_SCREEN:
            if x['flag'] == 'n':
                Label(dbFrame, text=x['name']).grid(row=i, column=0)
                i+=1

    def updateEntry(e):
        message("")
        for l in arr_lvar:
            if elb.get(l):
                elb[l].grid_forget()
            if ent.get(l):
                ent[l].grid_forget()
        
        btn_f_p.grid_forget()
        btn_f_m.grid_forget()

        for x in ARR_SCREEN:
            if x['name'] == selLabel:
                
                ent['posX'].grid(row=4, column=0)
                ent['posY'].grid(row=4, column=1, columnspan=2)
                elb['width'].grid(row=6, column=0, sticky="w", pady=2, padx=4)
                ent['width'].grid(row=6, column=1, sticky="w", ipadx=3)
                ent['height'].grid(row=6, column=2, sticky="w", ipadx=3)
                elb['padding'].grid(row=7, column=0, sticky="w", pady=2, padx=4)
                ent['padX'].grid(row=7, column=1, sticky="w", ipadx=3)
                ent['padY'].grid(row=7, column=2, sticky="w", ipadx=3)
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

                if selLabel.startswith('picture') :
                    elb['url'].grid(row=1, column=0, sticky="w", pady=2, padx=4)
                    ent['url'].grid(row=1, column=1, columnspan=2, sticky="w")
                    lvar['url'].set(x.get('url'))

                elif selLabel.startswith('snapshot') or selLabel.startswith('video'):
                    elb['device_info'].grid(row=1, column=0, sticky="w", pady=2, padx=4)
                    ent['device_info'].grid(row=1, column=1, columnspan=2, sticky="w")
                    lvar['device_info'].set(x.get('device_info'))
                    ent['device_info'].configure(values=listDev)
                    for i, ft in enumerate(listDev):
                        if x.get('device_info') == ft:
                            ent['device_info'].current(i)
                    

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
                    elb['bgcolor'].grid(row=5, column=0, sticky="w", pady=2, padx=4)
                    ent['bgcolor'].grid(row=5, column=1, columnspan=2, sticky="w")

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

                    for i, ft in enumerate(listFontColor):
                        if x.get('color')[1] == ft:
                            ent['bgcolor'].current(i)

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
        writeScreenData()
        message("saved")
        # json_str = json.dumps(arr, ensure_ascii=False, indent=4, sort_keys=True)
        # with open("%s\\%s" %(_ROOT_DIR, ARR_CONFIG['template']), "w", encoding="utf-8") as f:
        #     f.write(json_str)

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
        global eWin
        fdir = os.path.dirname(lvar['url'].get())
        fname = filedialog.askopenfilename(initialdir=fdir , title="Select imagefile", filetypes=[("image", ".jpeg"),("image", ".png"),("image", ".jpg"),])
        print(fname)
        lvar['url'].set(fname)
        eWin.lift()

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

    if selLabel:
        updateEntry(0)
    else:
        showEntry()

def canvasLabel(canvas, text="", option={}) :

    pass

if __name__ == '__main__':
    root =Tk()
    screen_width = root.winfo_screenwidth()
    screen_height = root.winfo_screenheight()

    root.geometry("%dx%d+0+0" %((screen_width), (screen_height)))

    root.bind('<Double-Button-1>', edit_screen)
    root.bind('<Button-3>', frame_option)
    root.configure(background="black")
    if ARR_CONFIG.get('background') == 'yes':
        # bg_label = Label(root)
        # bg_img = "bg.jpg"
        # if os.path.isfile(bg_img):
        #     bg_img = cv.imread(bg_img, cv.IMREAD_UNCHANGED)
        #     bg_img = cv.cvtColor(bg_img, cv.COLOR_BGR2RGB)
        #     bg_img = Image.fromarray(bg_img)
        #     # bg_img = bg_img.resize((screen_width, screen_height), Image.LANCZOS)
        #     bg_img = bg_img.resize((screen_width, screen_height))
        #     imgtk = ImageTk.PhotoImage(image=bg_img)
        #     # menus[name].create_image(0, 0, anchor="nw", image=imgtk)
        #     bg_label.configure(image=imgtk)
        #     bg_label.photo=imgtk # phtoimage bug
        #     bg_label.place(x=-2, y=-2)

        bg_canvas = Canvas(root, width=screen_width, height=screen_height)
        bg_img = "bg.jpg"
        if os.path.isfile(bg_img):
            var['bg']= StringVar()
            bg_img = cv.imread(bg_img)
            bg_img = cv.cvtColor(bg_img, cv.COLOR_BGR2RGB)
            bg_img = Image.fromarray(bg_img)

            bg_img = bg_img.resize((screen_width, screen_height), Image.LANCZOS)
            imgtk = ImageTk.PhotoImage(image=bg_img)
            # menus[name].create_image(0, 0, anchor="nw", image=imgtk)
            # bg_label.configure(image=imgtk)
            bg_canvas.create_image(0,0, image=imgtk, anchor="nw")
            bg_canvas.photo=imgtk # phtoimage bug
            xt = bg_canvas.create_text(500,130, text="HELLO", fill="red", font=('simhei', 20, 'bold'))
            bg_canvas.place(x=0, y=0)
            bg_canvas.itemconfig(xt,text="Hans Kim")
            bg_canvas.move(xt, 400,200)

    ths = procScreen()
    ths.start()

    thd = getDataThread()
    thd.start()
    
    # thv = showPicture()
    # thv.start()

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

