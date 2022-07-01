change_log = """
########################################################################################
# Monitor program for 0.9x
2020-11-20, build 100
2021-03-24, windows program and python3 only
2021-08-14, monitor.py with windows, \\bin\\

"""
# wget http://49.235.119.5/download.php?file=../bin/monitor.py -O /var/www/bin/monitor.py

#Windows system only

_version = 0.94
import os, sys, time
from threading import Timer, Thread, Event
import locale

if (os.name == 'nt'):
	from tkinter import *
	from tkinter import ttk
	import winreg

else :
	print ("This program runs on windows system only")
	sys.exit()

absdir = os.path.dirname(os.path.abspath(sys.argv[0]))
rootdir = os.path.dirname(absdir)
print (rootdir)



# wmic process where "name='python.exe' or name='python3.exe'" get processid, commandline 
# taskkill /F /PID processid


# def status_process():
# 	st = dict()
# 	if os.name == 'nt':
# 		a = os.popen("""wmic process where "name='mysqld.exe' or name='php-cgi.exe' or name='nginx.exe' or name='python.exe' or name='python3.exe'" get caption, processid, commandline, executablePath""")
# 		p = a.read()

# 	elif os.name == 'posix':
# 		a = os.popen("ps -ef ")
# 		p= a.read()

# 	# print (p)
# 	for line in p.splitlines():
# 		print (line)
# 		tab = line.split("\t")
# 		print(tab)
 
# 	if LOCALE[0] == 'zh_CN':
# 		st[0] = "运行中" if p.find("mysql") >0  else "已停止"
# 		st[1] = "运行中" if p.find("nginx") >0 else "已停止"
# 		st[2] = "运行中" if (p.find("php-fpm") >0 or  p.find("php-cgi")>0) else "已停止"
# 		st[3] = "运行中" if p.find("startBI") >0 else "已停止"

# 	else :
# 		st[0] = "Running" if p.find("mysql") >0  else "Stopped"
# 		st[1] = "Running" if p.find("nginx") >0 else "Stopped"
# 		st[2] = "Running" if (p.find("php-fpm") >0 or  p.find("php-cgi")>0) else "Stopped"
# 		st[3] = "Running" if p.find("startBI") >0 else "Stopped"

# 	if os.name == 'nt':
# 		for i in range(0,4):
# 			varSt[i].set(st[i])

def status_process(mode = 0):
	global PROBE_INTERVAL
	st = dict()
	arr_rs = {
		"mysqld":{"status":"Stopped","path":"wrong", "code":0},
		"nginx":{"status":"Stopped","path":"wrong", "code":0},
		"php-cgi":{"status":"Stopped","path":"wrong", "code":0},
		"startbi":{"status":"Stopped","path":"wrong", "code":0},
	}
	a = os.popen("""wmic process where "name='mysqld.exe' or name='php-cgi.exe' or name='nginx.exe' or name='python.exe' or name='python3.exe'" get caption, processid, commandline, executablePath""")
	lines = str(a.read()).splitlines()
	for line in lines:
		for rs in arr_rs:
			if line.lower().find(rs) >=0 :
				arr_rs[rs]['status'] = "Running"
				if line.lower().find(rootdir.lower()) >=0:
					arr_rs[rs]['code'] = 1
				else :
					arr_rs[rs]['code'] = -1
				for tab in line.split(" "):
					if rs == 'startbi':
						if tab.lower().find("\\python") >=0 :
							arr_rs[rs]['path'] = tab.strip()
					else :	
						if tab.lower().find("\\"+rs.lower()) >=0 :
							arr_rs[rs]['path'] = tab.strip()
						
	
	for rs in arr_rs:
		print (rs, arr_rs[rs])
		


	
	if mode :
		return arr_rs
	
	
	# if LOCALE[0] == 'zh_CN':
	# 	st[0] = "运行中" if arr_rs["mysqld"]['code'] == 1  else "已停止(%d)" %arr_rs["mysqld"]['code'] 
	# 	st[1] = "运行中" if arr_rs["nginx"]['code'] == 1 else "已停止(%d)" %arr_rs["nginx"]['code']
	# 	st[2] = "运行中" if arr_rs["php-cgi"]['code'] == 1 else "已停止(%d)" %arr_rs['php-cgi']['code']
	# 	st[3] = "运行中" if arr_rs['startbi']['code']==1 else "已停止(%d)" %arr_rs['startbi']['code']

	# else :
	# 	st[0] = "Running" if arr_rs["mysqld"]['code'] == 1  else "Stopped(%d)" %arr_rs["mysqld"]['code']
	# 	st[1] = "Running" if arr_rs["nginx"]['code'] == 1 else "Stopped(%d)" %arr_rs["nginx"]['code']
	# 	st[2] = "Running" if arr_rs["php-cgi"]['code'] == 1 else "Stopped(%d)" %arr_rs['php-cgi']['code']
	# 	st[3] = "Running" if arr_rs['startbi']['code']==1 else "Stopped(%d)" %arr_rs['startbi']['code']

	zh_CN = {"Running": "运行中", "Stopped": "已停止"}
	for i, rs in enumerate(arr_rs):
		if LOCALE[0] == 'zh_CN':
			arr_rs[rs]['status'] = zh_CN[arr_rs[rs]['status']]
		if arr_rs[rs]['code']  != 1:
			arr_rs[rs]['status'] +="(%d)" %arr_rs[rs]['code']
		varSt[i].set(arr_rs[rs]['status'])
		varPath[i].set(arr_rs[rs]['path'])
		

def start_services_windows():
	a = os.popen("tasklist")
	p = a.read().upper()
	#MYSQL
	if p.find("MYSQL"):
		if os.path.isdir(rootdir +  "\\MariaDB\\bin"):
			os.chdir(rootdir + "\\MariaDB\\bin")
		elif os.path.isdir(rootdir +  "\\Mysql\\bin"):
			os.chdir(rootdir + "\\MariaDB\\bin")
		
		b = os.system("start RunHiddenConsole.exe mysqld.exe")	

		print("Mysqld Startd")
		time.sleep(10)
			
	#PHP
	if p.find("PHP-CGI") :
		os.chdir(rootdir + "\\php")
		b = os.system('start "PHP-CGI 127.0.0.1:9000" RunHiddenConsole.exe php-cgi.exe -q -c php.ini -b 127.0.0.1:9000')
		print ("PHP-CGI Startd")
		time.sleep(2)
        
	#Nginx	
	if p.find("NGINX"):
		os.chdir(rootdir + "\\NGINX")
		a = os.system("start nginx.exe")
		print("NGINX Startd")
		time.sleep(2)

	status_process()

	
def stop_services_windows(cat = ''):
	if cat == 'nginx' or not cat:
		p = os.popen("taskkill /F /IM nginx.exe > nul")
		print (p.read())
	if cat == 'php' or not cat	:
		p = os.popen("taskkill /F /IM php-cgi.exe > nul")
		print (p.read())
	if cat == 'mysql' or not cat:
		# p = os.popen("taskkill /F /IM mysqld.exe > nul")
		p = os.popen("%s/Mariadb/bin/mysqladmin -uroot -prootpass shutown" %rootdir)
		print (p.read())

	status_process()

def start_commands_windows():
	a = os.popen("""wmic process where "name='python.exe' or name='python3.exe'" get commandline""")
	p = a.read().upper()
	if p.find("STARTBI") < 0:
		os.chdir(rootdir + "\\BIN")
		p = os.system("RunHiddenConsole.exe python3.exe startBI.py")
		print ("service started")
	status_process()	

def stop_commands_windows():
	sline = ""
	a = os.popen("""wmic process where "name='python.exe' or name='python3.exe'" get caption, processid, commandline""")
	p = a.read()
	print(p)
	lines = p.splitlines()
	for line in lines:
		if line.find("startBI") >0:
			sline = line
			break
	if not sline:
		return False
	tabs = sline.split(" ")
	for tab in tabs:
		try :
			pid = int(tab)
		except Exception as e:
			pass
	print (pid)
	a = os.popen("taskkill /F /PID %d" %pid)
	print (a.read())
	time.sleep(1)
	status_process()
	
class PT():
	def __init__(self, t, hFunction):
		self.t = t
		self.hFunction = hFunction
		self.thread = Timer(self.t, self.handle_function)

	def handle_function(self):
		self.hFunction()
		self.thread = Timer(self.t, self.handle_function)
		self.thread.start()

	def start(self):
		self.thread.start()	

	def cancel(self):
		self.thread.cancel()	





if __name__ == '__main__':
	LOCALE = locale.getdefaultlocale()
	print (LOCALE)	
	print ("BI Monitor Version %.2f" %_version)
	ret = {'code':1, 'message':'' }
	PROBE_INTERVAL = 5

	if os.name == 'nt':
		window = Tk()
		window.protocol("WM_DELETE_WINDOW", window.destroy)
		window.title("BI Monitor %.2f" %_version)
		window.geometry("460x200")
		window.resizable(True, True)

		frStat = Frame(window, bd=0, relief="solid")
		frStat.pack(side="top", expand=True, padx=10, pady=5)

		lblProcess = Label(frStat, text="Process")
		lblProcess.grid(row=0, column=0, sticky="news", ipadx=5)
		lblStatus = Label(frStat, text="Status")
		lblStatus.grid(row=0, column=1, sticky="news", ipadx=10)
		# lblButton = Label(frStat, text="Operation")
		# lblButton.grid(row=0, column=2, sticky="news", ipadx=10)
		lblPath = Label(frStat, text="path")
		lblPath.grid(row=0, column=2, sticky="news", ipadx=10)


		arr_cmd = ['mysql', 'nginx', 'php', 'startBi']
		varSt= [None] * len(arr_cmd)
		varPath= [None] * len(arr_cmd)
		for i, col in enumerate(arr_cmd):
			Label(frStat, text="{0}".format(col)).grid(row=i+1, column=0, sticky="w", ipadx=10)
			varSt[i] = StringVar(); varSt[i].set("----")
			varPath[i] = StringVar(); varPath[i].set("----")
			Label(frStat, textvariable = varSt[i]).grid(row=i+1, column=1, sticky="w", ipadx=20)
			# Button(frStat, text = "Run", command=start_services_windows).grid(row=i+1, column=2, sticky="w", ipadx=20)
			Label(frStat, textvariable = varPath[i]).grid(row=i+1, column=2, sticky="w", ipadx=20)

		frCtrl = Frame(window, bd=0, relief="solid")
		frCtrl.pack(side="top", expand=True, padx=5, pady=0)
		btnStat = Button(frCtrl, text="Status", command=status_process, width=6, height=1)
		btnStat.pack(side="left", padx=5)
		btnStart = Button(frCtrl, text="Run", command=start_commands_windows, width=6, height=1)
		btnStart.pack(side="left", padx=5)
		btnStop = Button(frCtrl, text="Stop", command=stop_commands_windows, width=6, height=1)
		btnStop.pack(side="left", padx=5)

		status_process()
		t = PT(int(PROBE_INTERVAL), status_process)
		t.start()

		if LOCALE[0] == 'zh_CN':
		# if LOCALE[0] == 'ko_KR':
			lblProcess.configure(text="程序")
			lblStatus.configure(text="状态")
			btnStart.configure(text = "运行")
			btnStop.configure(text = "停止")
			btnStat.configure(text="状态")

		window.mainloop()
		t.cancel()
