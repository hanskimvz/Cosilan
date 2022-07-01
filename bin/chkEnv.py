#network Environment
#PHP, NGINX, MYSQL Environment
#config file enviroment

import os, time, sys
from http.client import HTTPConnection
import socket
import pymysql
import sqlite3
import re, base64, struct

def is_online(device_ip):
	if os.name == 'nt':
		cmd_str = "ping -n 1 -w 2 %s > nul" %device_ip
	else :
		cmd_str = "ping -c 1 -w 2 %s > /dev/null 2>&1" %device_ip
	exit_code = os.system(cmd_str)

	if(exit_code == 0) :
		return True
	return False

def arp_device():
	dev_idx =[]
	if os.name == 'nt':
		cmd = 'arp -a |findstr "00-13-2"'
	else :
		cmd = "arp -n |grep 00:13:2"
	arp_regex = re.compile(r"([0-9.]+)(\s+)([\w:]+)(.+)", re.IGNORECASE)
	p = os.popen(cmd).read()
	p = p.replace("ether",""); 	p = p.replace("-",""); 	p = p.replace(":","")
	
	lines = p.splitlines()
	for i, line in enumerate(lines):
		regex = arp_regex.search(line)
		if regex:
			location = regex.group(1)
			mac = regex.group(3).upper()
			# print (location, mac)
		else :
			continue

		online = is_online(location)
		dev_idx.append({"idx":i, "location":location, "mac":mac, "online": online })
		
	return dev_idx

def list_device(debug='n') :
    dev_idx = []
    body = set()
    locations = set() # set  -> no order, no duplicate, no empty
    ST = 'urn:schemas-upnp-org:device:nvcdevice'
    msg = \
        'M-SEARCH * HTTP/1.1\r\n' \
        'HOST:239.255.255.250:1900\r\n' \
        'ST:' + ST + '\r\n'\
        'MX:2\r\n' \
        'MAN:"ssdp:discover"\r\n' \
        '\r\n'

    location_regex = re.compile(r"location: http://[ ]*(.+):(\d+)/upnpdevicedesc.xml\r\n", re.IGNORECASE)
    usn_mac_regex  = re.compile(r"USN: uuid:(\w{9})-(\S{17})(.*)", re.IGNORECASE)	
    model_regex    = re.compile(r"<modelName>(.+)</modelName>", re.IGNORECASE)	
    brand_regex    = re.compile(r"<manufacturer>(.+)</manufacturer>", re.IGNORECASE)
    # Set up UDP socket
    s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM, socket.IPPROTO_UDP)
    s.settimeout(2)
    s.sendto(msg.encode('ASCII'),('239.255.255.250', 1900))

    try:
        while True: 
            # buffer size is 1024 bytes
            data, addr = s.recvfrom(1024) 
            body.add(data.decode('ASCII'))
    except socket.error as e:
        s.close()

    for i, block in enumerate(body):
        location_result = location_regex.search(block)
        usn_mac = usn_mac_regex.search(block)

        if location_result and (location_result.group(1) in locations) == False:
            online = is_online(location_result.group(1))
            model = ''
            brand = ''

            if online:
                server = (location_result.group(1), str(location_result.group(2)))
                conn = HTTPConnection(*server)
                conn.putrequest("GET", '/upnpdevicedesc.xml') 
                conn.endheaders()
                rs = conn.getresponse().read().decode("utf-8")
                conn.close()
                if rs.find("<modelName>") >=0 :
                    model = model_regex.search(rs).group(1)
                if rs.find("<manufacturer>") >=0 :
                    brand = brand_regex.search(rs).group(1)
            
            usn = usn_mac.group(1)
            location = location_result.group(1)
            mac = usn_mac.group(2).replace(':','')
            dev_idx.append({"idx":i, "usn" : usn, "location":location, "mac":mac, "model":model, "brand":brand, "online":online})
            
            locations.add(location)
            
    return dev_idx 

def connectCloudServer():
    a = is_online('49.235.119.5')
    print (a)

def report_network():
    arr = arp_device()
    print ("== Browsing with Arp ==")
    print ("idx\tlocation\tmac\t\tonline")
    for tab in arr:
        print ("%d\t%s\t%s\t%s" %(int(tab['idx']+1), tab['location'], tab['mac'], tab['online']))

    arr =  list_device()
    print ()
    print ("== Browsing with upnp ==")
    print ("idx\tlocation\tmac\t\tonline\tusn\t\tmodel\t\tbrand")

    for tab in arr:
        if len(tab["model"]) <8:
            tab["model"] += "\t"

        print ("%d\t%s\t%s\t%s\t%s\t%s\t%s" %(int(tab['idx']+1), tab['location'], tab['mac'], tab['online'], tab["usn"], tab["model"], tab["brand"]))
    
    connectCloudServer()

if __name__ == '__main__':
    report_network()