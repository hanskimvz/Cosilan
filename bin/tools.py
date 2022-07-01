import os, sys
import socket
def getLocalIP():
    # s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
    # s.connect(('localhost', 80))
    # print (s.getsockname())
    # s.close()
    if os.name == 'nt':
        a = socket.getaddrinfo(socket.gethostname(), None, 2, 1, 0)
    print (a)


getLocalIP()
sys.exit()

from functions_s import _PLATFORM, _ROOT_DIR, info_from_db


print(_PLATFORM)
print (_ROOT_DIR)
print(info_from_db(title="startBI", type="txt"))
sys.exit()

















absdir = os.path.dirname(os.path.abspath(sys.argv[0]))
os.chdir(absdir)
rootdir = os.path.dirname(absdir)


def getServiceSt():
    print (os.getcwd())
    if os.name == 'posix':
        arr_rs = {
            "mysqld" :{"status":"stopped","path":"wrong", "code":0},
            "nginx"  :{"status":"stopped","path":"wrong", "code":0},
            "php-fpm":{"status":"stopped","path":"wrong", "code":0},
            "startbi":{"status":"stopped","path":"wrong", "code":0},
        }
        # for line in os.popen(""" ps -ef |grep -P "nginx|php|startBi|mysqld" | grep -v "grep" """).read().splitlines():
        for line in os.popen(""" ps -ef """).read().splitlines():
            line = line.lower().strip()
            if not line:
                continue
            for rs in arr_rs:
                if line.find(rs) >= 0 :
                    arr_rs[rs]['status'] = "running"
                    arr_rs[rs]['code'] = 1

        # for rs in arr_rs:
        #     if arr_rs[rs]['status'] == "running":
        #         arr_rs[rs]['path'] = os.popen("which %s " %rs).read().strip()

    elif os.name == 'nt':
        arr_rs = {
            "mysqld" :{"status":"stopped","path":"wrong", "code":0},
            "nginx"  :{"status":"stopped","path":"wrong", "code":0},
            "php-cgi":{"status":"stopped","path":"wrong", "code":0},
            "startbi":{"status":"stopped","path":"wrong", "code":0},
        }

        cmd_str = """wmic process where "name='mysqld.exe' or name='php-cgi.exe' or name='nginx.exe' or commandline='python3.exe startBI.py'" get caption, commandline, executablePath"""
        for line in str(os.popen(cmd_str).read()).splitlines():
            line = line.lower().strip()
            if not line:
                continue
            for rs in arr_rs:
                if line.lower().find(rs) >= 0 :
                    arr_rs[rs]['status'] = "running"
                    tabs  =  line.split(" ")
                    arr_rs[rs]['path'] = os.path.dirname(tabs[-1])
                    arr_rs[rs]['code'] = 1 if arr_rs[rs]['path'].find(rootdir) >=0 else -1

    return arr_rs

x = getServiceSt()

print(x)

# from init_db import init_db_main


# init_db_main()