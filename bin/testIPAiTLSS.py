from cgi import test
import os, time, sys

# from http.client import HTTPConnection
# from configparser import ConfigParser
# import socket
# import re, base64, struct
# from tokenize import Hexnumber
# from urllib import request
# from urllib.parse import urlparse, parse_qsl, unquote
# import threading
# import pymysql
# import logging, logging.handlers
# import sqlite3
# import signal
# import json
# import requests

def checkIPAICGI():
    from functions_s import checkAuthMode, active_cgi
    str_cgi = [
        # "/cgi-bin/about.cgi?action=view&msubmenu=about",
        "/cgi-bin/operator/param.cgi?group=all",
        "/cgi-bin/admin/vca-api/api.json",
        "/cgi-bin/admin/network.cgi?msubmenu=ip&action=view",
        "/cgi-bin/operator/countreport.cgi?reportfmt=csv&to=now&counter=active&sampling=600&order=ascending&value=diff&from=2022-03-09",
        "/cgi-bin/operator/snapshot.cgi",
        # "done\n"
    ]

    dev_ip = "192.168.132.6"
    userid = "root"
    userpw = "Rootpass12345"

    dev_ip = "192.168.1.190"
    userid = "root"
    userpw = "pass"


    authkey, device = checkAuthMode(dev_ip, userid, userpw)
    # authkey = HTTPDigestAuth(userid, userpw)
    print(authkey, device)
    # if device:
    #     for i, cgi in enumerate(str_cgi):
    #         rs = active_cgi(dev_ip, authkey, cgi)
    #         with open("a%d.jpg" %i, "wb") as f:
    #             f.write(rs)


    

def testTLSS():
    import socket
    from functions_s import tlss_cgi, recv_tlss_message, send_tlss_command, check_device_family, checkAuthMode
    str_cgi =dict()
    str_cgi['IPAI'] = [
        "/cgi-bin/admin/param.cgi?group=all",
        "/cgi-bin/operator/countreport.cgi?reportfmt=csv&to=now&counter=active&sampling=600&order=ascending&value=diff&from=2022/03/09",
        "/cgi-bin/video.cgi",
    ]
    str_cgi['IPN'] = [
        "/uapi-cgi/param.cgi?action=list",
        "/cgi-bin/operator/countreport.cgi?reportfmt=csv&to=now&counter=active&sampling=600&order=ascending&value=diff&from=2022/03/09",
        "/nvc-cgi/operator/snapshot.fcgi",
    ]
    str_cgi['IPE'] = [ # IPE, does not support TLSS
        "cgi-bin/operator/countreport.cgi?reportfmt=csv&to=now&counter=active&sampling=600&order=ascending&value=diff&from=2022/03/09",
        "nvc-cgi/operator/snapshot.fcgi",
    ]    

    s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    s.bind(('', 65000))
    s.listen(30) 
    for i in range(2):
        conn, addr = s.accept()
        device_info = recv_tlss_message(conn, timeout=2)
        dev = check_device_family(conn)
        print(device_info, dev)

        x=0
        for cgi in str_cgi[dev]:
            print(cgi)
            data = tlss_cgi(conn, cgi, timeout=1)
            print(data)
            with open("%s.%d.jpg" %(dev, x), "wb") as f:
                if dev == 'IPN':#or dev == 'IPE':
                    spos = data.index(b"\n\r")
                    data = data[spos:].lstrip()
                f.write(data)

            x+=1
            time.sleep(1)
        send_tlss_command(conn,"done\0")
        time.sleep(2) # wait for client disconnect
        conn.close()
    s.close()

def testGetSnapshot():
    import socket
    from functions_s import checkAuthMode, send_tlss_command, recv_tlss_message, check_device_family
    from counting_main import getSnapshot
    
    dev_ip = "192.168.1.28" ;    userid = 'root';     userpw = 'pass'
    dev_ip = "192.168.1.190";    userid = 'root';     userpw = 'pass'
    # dev_ip = "192.168.132.6";    userid = 'root';     userpw = 'Rootpass12345'

    
    authkey, dev = checkAuthMode(dev_ip, userid, userpw)

    data = getSnapshot(device_ip=dev_ip, device_family=dev,authkey= authkey)
    with open("a.jpg", "wb") as f:
        f.write(data)
    
    s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    s.bind(('', 65000))
    s.listen(30) 

    conn, addr = s.accept()
    data = recv_tlss_message(conn, timeout=2)
    print (data)
    dev = check_device_family(conn)
    print(dev)
    data = getSnapshot(conn=conn, device_family=dev)
    with open("b.jpg", "wb") as f:
        f.write(data)
    send_tlss_command(conn,"done\0")
    time.sleep(2) # wait for client disconnect
    conn.close()
    s.close()    

def testGetParam():
    import socket
    from functions_s import checkAuthMode, send_tlss_command, recv_tlss_message, check_device_family
    from counting_main import getParam
    
    dev_ip = "192.168.1.28" ;    userid = 'root';     userpw = 'pass'
    dev_ip = "192.168.1.190";    userid = 'root';     userpw = 'pass'
    dev_ip = "192.168.1.136";    userid = 'root';     userpw = 'Rootpass12345'
    
    authkey, dev = checkAuthMode(dev_ip, userid, userpw)

    data = getParam(device_ip=dev_ip, device_family=dev, authkey= authkey)
    print (data)
    # with open("c.txt", "wb") as f:
    #     f.write(data)
    
    # s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    # s.bind(('', 65000))
    # s.listen(30) 

    # # conn, addr = s.accept()
    # # data = recv_tlss_message(conn, timeout=2)
    # # print (data)
    # # dev = check_device_family(conn)
    # # print(dev)
    # # data = getParam(conn=conn, device_family=dev)
    # # print(data)
    # # # with open("b.txt", "wb") as f:
    # # #     f.write(data)
    # # send_tlss_command(conn,"done\0")
    # # time.sleep(2) # wait for client disconnect
    # # conn.close()
    # # s.close()  

def testGetCountReport():
    import socket
    from functions_s import checkAuthMode, send_tlss_command, recv_tlss_message, check_device_family
    from counting_main import getCountReport
    
    dev_ip = "192.168.1.28" ;    userid = 'root';     userpw = 'pass'
    dev_ip = "192.168.1.190";    userid = 'root';     userpw = 'pass'
    dev_ip = "192.168.8.128";    userid = 'root';     userpw = 'pass'
    dev_ip = "192.168.1.136";    userid = 'root';     userpw = 'Rootpass12345'
    
    authkey, dev = checkAuthMode(dev_ip, userid, userpw)
    print(authkey, dev)
    if(authkey and dev):
        data = getCountReport(device_ip=dev_ip, device_family=dev, authkey= authkey)
        print(data)
        # with open("a.txt", "wb") as f:
        #     f.write(data)

    s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    s.bind(('', 65000))
    s.listen(30) 

    conn, addr = s.accept()
    data = recv_tlss_message(conn, timeout=2)
    print (data)
    dev = check_device_family(conn)
    print(dev)
    data = getCountReport(conn=conn, device_family=dev)
    print(data)
    # with open("b.txt", "wb") as f:
    #     f.write(data)
    send_tlss_command(conn,"done\0")
    time.sleep(2) # wait for client disconnect
    conn.close()
    s.close()  

def testUpnp():
    from functions_s import list_device, ssdp_device, arp_device
    print ("arp")
    x = arp_device()
    for z in x:
        print(z)
    
    print("ssdp")
    x = ssdp_device()
    for z in x:
        print(z)

    print("list device")
    x = list_device()
    for z in x:
        print(z)                

def testArp():
    from functions_s import arp_device

    x = arp_device()
    for z in x:
        print(z)

# checkIPAICGI()
# testTLSS()
testUpnp()
# testArp()
# testGetSnapshot()
# testGetParam()
# testGetCountReport()












# def recv_timeout(conn,timeout=2):
# 	conn.setblocking(0)
# 	total_data=[]
# 	data=''
# 	begin=time.time()
# 	while 1:
# 		if total_data and time.time()-begin > timeout:
# 			break
# 		elif time.time()-begin > timeout*2:
# 			break
			
# 		try:
# 			data = conn.recv(1024)
# 			if data:
# 				total_data.append(data)
# 				begin=time.time()
# 			else:
# 				time.sleep(0.1)
# 		except:
# 			pass
# 	return  b''.join(total_data)

# def send_tlss_command(conn, cmd=''):
# 	length = len(cmd)
# 	s_num = struct.pack("BBBB", length&0xFF, (length>>8)&0xFF, (length>>16)&0xFF, (length>>24)&0xFF)
# 	rs = "send_message: length:%d, num:%d, %s" %(length, len(s_num), cmd)
# 	try:
# 		conn.send(s_num)
# 		conn.send(cmd.encode('ascii')) # send byte type
# 	except:
# 		pass
# 	return rs

# def recv_tlss_message(conn, timeout=2):
#     conn.setblocking(1)
#     data_num =  conn.recv(4)
#     try: 
#         # num = ord(data_num[0]) + (ord(data_num[1])<<8) + (ord(data_num[2])<<16) + (ord(data_num[3])<<24)
#         num = int("%02X%02X%02X%02X" %(data_num[3],data_num[2],data_num[1],data_num[0]), 16)
#     except:
#         num = 0

#     print(num)
#     num = 0
#     if num :
#         rs = conn.recv(num)
#     else :
#         rs = recv_timeout(conn, timeout)
#     return rs

# def is_online(ip, port=80):
# 	s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
# 	server = (ip, port)
# 	s.settimeout(1)
# 	try:
# 		s.connect(server)
# 	except Exception as e:
# 		# print(e)
# 		s.close()
# 		return False
	
# 	s.close()
# 	return True	

# def checkAuthMode(dev_ip, userid='root', userpw='pass'):
#     dev_type= None
#     auth = None
#     arr_dev = [
#         ('IPN', 'http://' + dev_ip + '/uapi-cgi/netinfo.cgi'),
#         ('IPAI', 'http://' + dev_ip + '/cgi-bin/admin/network.cgi'),
#         ('IPE',  'http://' + dev_ip + '/operator/about_configuration.shtml')
#     ]
#     arr_auth = [HTTPDigestAuth(userid, userpw), HTTPBasicAuth(userid, userpw)]
#     if not is_online(dev_ip):
#         return (auth, dev_type)
#     for dev, url in arr_dev:
#         for authkey in arr_auth:
#             r = requests.get(url,  auth=authkey)
#             if r.text.find('404') < 0 and r.text.find('Not Found')<0:
#                 dev_type = dev
#                 if r.text.find('401') < 0 and r.text.find('Unauthorized')<0:
#                     auth = authkey
#                     break

#     return (auth, dev_type)
#     # return auth false if userid and password are not correct
#     # return dev_type false if dev_ip cannot be reachable

# def list_device(debug='n') :
#     dev_idx = []
#     body = set()
#     locations = set()
#     # set  -> no order, no duplicate, no empty
#     ST = 'urn:schemas-upnp-org:device:nvcdevice'
#     ST = 'upnp:rootdevice'
#     msg = \
#         'M-SEARCH * HTTP/1.1\r\n' \
#         'HOST:239.255.255.250:1900\r\n' \
#         'ST:' + ST + '\r\n'\
#         'MX:2\r\n' \
#         'MAN:"ssdp:discover"\r\n' \
#         '\r\n'

#     location_regex = re.compile(r"location: http://[ ]*(.+):(\d+)/upnpdevicedesc.xml\r\n", re.IGNORECASE)
#     usn_mac_regex  = re.compile(r"USN: uuid:(\w{9})-(\S{17})(.*)", re.IGNORECASE)	
#     model_regex    = re.compile(r"<modelName>(.+)</modelName>", re.IGNORECASE)	
#     brand_regex    = re.compile(r"<manufacturer>(.+)</manufacturer>", re.IGNORECASE)
#     # Set up UDP socket
#     s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM, socket.IPPROTO_UDP)
#     s.settimeout(5)
#     s.sendto(msg.encode('ASCII'),('239.255.255.250', 1900))

#     try:
#         while True: 
#             # buffer size is 1024 bytes
#             data, addr = s.recvfrom(65507) 
#             print (data)
#             body.add(data.decode('ASCII'))

#     except socket.error as e:
#         print(e)
#         s.close()

#     return body


# msg =('M-SEARCH * HTTP/1.1\r\n' +
#     # 'HOST: 192.168.1.2:2177\r\n' +
#     'HOST: 239.255.255.250:1900\r\n' +
#     'MAN: "ssdp:discover"\r\n' +
#     'MX: 5\r\n' +
#     'ST: ssdp:all\r\n' +
#     '\r\n')

# msgn = ('NOTIFY * HTTP/1.1\r\n' +
#     'HOST: 239.255.255.250:1900\r\n' +
#     'NT:upnp:rootdevice\r\n'+
#     'NTS:ssdp:alive\r\n' +
#     'Server: Net-OS 5.xx UPnp/1.0\r\n' +
#     'Location:\r\n'+
#     'Cache-Control:max-age=120\r\n'+
#     'USN:HELLOTHISISHANS\r\n' +
#     '\r\n'
# ) 
# # Set up UDP socket
# print (msg)
# s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM, socket.IPPROTO_UDP)
# s.settimeout(2)
# s.sendto(msg.encode(), ('239.255.255.250', 1900) )

# try:
#     while True:
#         data, addr = s.recvfrom(65507)
#         print (addr, data)
# except socket.timeout:
#     s.close()
#     pass

# sys.exit()

# url = "192.168.1.190"
# url = "192.168.1.28"
# # url = "192.168.132.6"
# rootid= 'root'
# rootpw = 'pass'
# # rootpw = 'Rootpass12345'

# authkey = checkAuthMode(url, rootid, rootpw)

# print(authkey)
# # r = requests.get('http://'+url+'/uapi-cgi/param.fcgi', auth=authkey)
# # print(r.content)

# sys.exit()

# str_cgi = [
#     "/cgi-bin/about.cgi?action=view&msubmenu=about",
#     "/cgi-bin/admin/param.cgi?group=all",
#     "/cgi-bin/admin/vca-api/api.json",
#     "/cgi-bin/operator/countreport.cgi?reportfmt=csv&to=now&counter=active&sampling=600&order=ascending&value=diff&from=2022-03-09",
#     "/cgi-bin/video.cgi",
#     "done\n"

# ]

# s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
# s.bind(('', 65000))
# s.listen(30) 
# conn, addr = s.accept()
# i=0
# for cgi in str_cgi:
#     data  = recv_tlss_message(conn, timeout=2)
#     with open("a%d.jpg" %i, "wb") as f:
#         f.write(data)
#     # print(data)
#     # for line in data.splitlines():
#     #     print(line)
    
#     print(send_tlss_command(conn, cgi))
#     i+=1
# time.sleep(2) # wait for client disconnect
# conn.close()
# s.close()