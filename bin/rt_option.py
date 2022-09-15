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

import os, sys, time
from tkinter import *
from tkinter import filedialog, ttk
import shutil
import json

from rt_main import _ROOT_DIR, lang, ARR_CONFIG, ARR_SCREEN, dbconMaster, getCRPT, parseRule, writeScreenData, writeConfig

oWin = None
eWin = None

var = dict()
root = None

ths = None
thd = None
thv = None

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

#########################################################################################################
############################################## Option Config ############################################
#########################################################################################################

# def edit_option(_root=None, arr_cfg={}):
def edit_option(win):
    global oWin, lang, var, ARR_CONFIG, root
    oWin = win
    var = dict()

    var['background'] = IntVar()
    var['refresh_interval'] = StringVar()
    var['full_screen'] = IntVar()
    var['template'] = StringVar()
    var['message_str'] = StringVar()

    for key in ARR_CONFIG['mysql']:
        var[key] = StringVar()
        var[key].set(ARR_CONFIG['mysql'][key])

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


    Message(win, textvariable = var['message_str'], width= 300,  bd=0, relief=SOLID, foreground='red').pack(side="top")

def closeOption():
    global oWin
    oWin.destroy()
    oWin = None


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
        need_restart = True

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

def message(strn):
    var['message_str'].set(strn)


#########################################################################################################
############################################## Screen Edit ##############################################
#########################################################################################################

def edit_screen(win, selLabel):
    global ARR_SCREEN, menus, eWin, editmode
    eWin = win
    editScreen(win)
    
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



root =Tk()