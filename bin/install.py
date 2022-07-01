import os, sys
from tkinter import *
import locale


absdir = os.path.dirname(os.path.abspath(sys.argv[0]))
os.chdir(absdir)
rootdir = os.path.dirname(absdir)
print (rootdir)
print()


def callCommand(comm):
    p = os.popen(comm)
    return p.read()

def PreviousProcess():
    arr_list= {
        'mysqld': {'pid':0, 'path':''},
        'nginx':  {'pid':0, 'path':''},
        'php':    {'pid':0, 'path':''},
        'startBi':{'pid':0, 'path':''},
    }
    if os.name == 'nt':
        cmd_str = ["startBI.exe", "startBI.py", "nginx.exe", "php-cgi.exe", "mysqld.exe"]
        for cmd in cmd_str:
            p = callCommand("""wmic process where name='%s' get processid, commandline, executablepath """ %cmd)

            lines = p.splitlines()
            for line in lines:
                if line.lower().find('startbi') > 0:
                    tabs = line.split(" ")
                    pid = 0 
                    for tab in tabs:
                        if not tab.strip():
                            continue
                        try:
                            pid = int(tab)
                            arr_list['startBi']['pid'] = pid
                        except:
                            continue


                # if pid:
                #     print( """%s taskkill /F /PID %d""" %(line,pid))

def copyPreviousDatabase():
    p = callCommand("""wmic process where name='mysqld.exe' get executablepath """ )
    print(p)

def start_install():
    pass

def win(window):

    window.title("Initialize Database")
    window.geometry("460x400")
    window.resizable(True, True)
    
    statusWin = LabelFrame(window, text="Status", width="400", height="400", borderwidth="2", padx="0", pady="0")
    statusWin.pack(side="top", fill="x", padx="10", pady="5")
    arr_cmd = ['mysql', 'nginx', 'php', 'startBi']
    varSt= [None] * len(arr_cmd)
    for i, col in enumerate(arr_cmd):
        Label(statusWin, text="{0}".format(col)).grid(row=i+1, column=0, sticky="w", ipadx=10)
        varSt[i] = StringVar()
        varSt[i].set("----")
        Label(statusWin, textvariable = varSt[i]).grid(row=i+1, column=1, sticky="w", ipadx=20)



    operateWin = LabelFrame(window, text="Operation", width="400", height="200", borderwidth="2", padx="0", pady="0")
    operateWin.pack(side="top", fill="x", padx="10", pady="10")
    chkCopy = Checkbutton(operateWin, text="Preseve previous database(copy database)")
    chkCopy.grid(row=0, column=0, columnspan="2", sticky="e", pady=15, padx=5, ipadx=10)
    btn_start = Button(operateWin, text="Start Install", command=start_install, width=10, height=1)
    btn_start.grid(row=1, column=0, sticky="e", pady=15, padx=5, ipadx=10)
    btn_cancel = Button(operateWin, text="Cancel", command=window.destroy, width=10, height=1)
    btn_cancel.grid(row=1, column=1, sticky="e", pady=15, padx=5, ipadx=10)



    if LOCALE[0] == 'zh_CN':
    # if LOCALE[0] == 'ko_KR':
        window.title("数据库初始化工具")
        lbl_title.configure(text = "安装工具")
        btn_start.configure(text="开始安装")
        btn_cancel.configure(text="取消")


    


if __name__ == '__main__':
    LOCALE = locale.getdefaultlocale()
    print (LOCALE)    
    window = Tk()
    win(window)

    PreviousProcess()


    window.mainloop()

