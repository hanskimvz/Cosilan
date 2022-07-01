change_log = """
2021-05-06, initial programming.

"""
# wget http://49.235.119.5/download.php?file=../bin/update.py -O /var/www/bin/update.py
import time, sys, os
import socket
from http.client import HTTPConnection
# import requests
import uuid


absdir = os.path.dirname(os.path.abspath(sys.argv[0]))
os.chdir(absdir)
rootdir = os.path.dirname(absdir)
# print(os.getcwd())

args = ""
for i, v in enumerate(sys.argv):
    if i==0 :
        continue
    args += v + " "


_SERVER_IP = '49.235.119.5'
_SERVER_PORT = 80
_SERVER_MAC = "525400C9FE37"


def is_online(ip, port=80):
	#  if port is not 80 ??
	s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
	server = (ip, port)
	s.settimeout(1)
	try:
		s.connect(server)
	except Exception as e:
		# print(e)
		s.close()
		return False
	
	s.close()
	return True	

def getMyPublicIP():
    _my_ip= ""
    server = ('api.ipify.org', 80)
    conn = HTTPConnection(*server)
    conn.putrequest("GET", "/") 
    try:
        conn.endheaders()    
        rs = conn.getresponse()    
        _my_ip =  rs.read().decode()
        conn.close()
        return _my_ip
    except:
        conn.close()
        pass

    server = ('ip.42.pl', 80)
    conn = HTTPConnection(*server)
    conn.putrequest("GET", "/raw") 
    try:
        conn.endheaders()    
        rs = conn.getresponse()    
        _my_ip =  rs.read().decode()
        conn.close()
        return _my_ip
    except:
        conn.close()
        pass

    return _my_ip


def checkAvailabe():
    if not is_online(_SERVER_IP):
        print ("unknown IP or cannot reach")
        return False

    # _MyPublicIP = getMyPublicIP()
    # if _SERVER_IP != "" and _SERVER_IP == _MyPublicIP :
    #         print ("SERVER IP and LOCAL MACHINE IP is the same, cannot updated.")
    #         return False
    mac = "%012X" %(uuid.getnode())
    if _SERVER_MAC == mac :
        print ("SERVER MAC and LOCAL MACHINE MAC is the same, cannot updated.")
        return False

    return True


def update():
    if not checkAvailabe():
        return False
    server = (_SERVER_IP, _SERVER_PORT)
    conn = HTTPConnection(*server)
    print ("Downloading update main file ....", end="")
    file = "bin/update_main.py"
    fname = "%s/%s" %(rootdir, file)
    
    conn.putrequest("GET", "/download.php?file=%s" %file) 
    conn.endheaders()
    rs = conn.getresponse()
    if rs:
        with open(fname, "wb")  as f:
            f.write(rs.read())
        
        print ("....download completed")
    conn.close()

    os.chdir("%s/bin" %rootdir)
    
    if os.name == 'nt':
        os.system("python3.exe update_main.py " + args)
    
    elif os.name == 'posix':
        os.system("/usr/bin/python3 ./update_main.py" )
    

if __name__ == '__main__':
    update()
    sys.exit()

