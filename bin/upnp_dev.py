import socket

ST = 'urn:schemas-upnp-org:device:nvcdevice'
ST = 'urn:schemas-upnp-org:device'
# ST = 'ssdp:all'
msg = \
    'M-SEARCH * HTTP/1.1\r\n' \
    'HOST:239.255.255.250:1900\r\n' \
    'ST:' + ST + '\r\n'\
    'MX:2\r\n' \
    'MAN:"ssdp:discover"\r\n' \
    '\r\n'

s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM, socket.IPPROTO_UDP)
s.settimeout(2)
s.sendto(msg.encode('ASCII'),('239.255.255.250', 1900))

try:
    while True: 
        # buffer size is 1024 bytes
        data, addr = s.recvfrom(1024) 
        print (data)
except socket.error as e:
    print (e)
    s.close()
s.close()

# msg = \
#     'M-SEARCH * HTTP/1.1\r\n' \
#     'HOST:192.168.1.1:1900\r\n' \
#     'MAN: "ssdp:discover"\r\n' \
#     'ST: ssdp:all\r\n' \
#     '\r\n'
# s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM, socket.IPPROTO_UDP)
# s.settimeout(5)
# s.sendto(msg.encode('ASCII'),('192.168.1.1', 1900))

# try:
#     while True: 
#         # buffer size is 1024 bytes
#         data, addr = s.recvfrom(1024) 
#         print (data)
# except socket.error as e:
#     print (e)
#     s.close()
# s.close()