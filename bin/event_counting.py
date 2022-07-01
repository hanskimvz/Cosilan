# 2022-03-26, always actived Event Counting, it doesnt matter whether TCP, HTTP. TCP mode starts with DOOFTEN~, it's like parsing problem.


import time, sys, os
import threading
import socket


from functions_s import (recv_timeout, configVars, addSlashes, log,  modifyConfig, _SERVER)

from parse_functions import  parseEventData
from db_functions import(updateEventCount, updateFaceThumnmail)


def getEventCounting(conn):
    data= recv_timeout(conn)
    conn.close()
    rs = parseEventData(data)
    return rs

def event_counting_thread(conn):
    arr_event = getEventCounting(conn)

    if arr_event[0]['type'] == 'counting':
        updateEventCount(arr_event)
    elif arr_event[0]['type'] == 'face':
        updateFaceThumnmail(arr_event)

def test():
    port= 5030
    s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    s.bind(('', port))
    s.listen(1) 

    for i in range(10):
        conn, addr = s.accept()
        msg = getEventCounting(conn)
        print(msg)
        print ()

    s.close()  

class  ThEventCounting(threading.Thread):
    def __init__(self):
        threading.Thread.__init__(self)
        self.daemon= True
    
    def run(self):
        print ("stating Event Counting")
        log.info ("starting Event Counting")        
        try:
            self.s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        except socket.error as  msg:
            log.critical("Could not create socket. Error Code: {0}, Error: {1}".format(str(msg[0], msg[1])))
            sys.exit(0)
        log.info("[-] Socket Created(COUNT_EVENT)")

        try:
            self.s.bind((configVars('software.service.count_event.host'), int(configVars('software.service.count_event.port'))))
            log.info("[-] Socket Bound to port {0}".format(str(configVars('software.service.count_event.port'))))
        
        except socket.error as msg:
            log.critical("EventCounting, Bind Failed. Error: {0}".format(str(msg)))
            print ("EventCounting: Bind Failed. Error: {0}".format(str(msg)))
            self.s.close()
            sys.exit()

        self.s.listen(30) 
        print("COUNT_EVENT Engine: Listening...") 

        while Running['count_event'] :
            self.conn, self.addr = self.s.accept()
            # log.info("COUNT_EVENT: %s:%s connected" %(self.addr[0], str(self.addr[1])))
            print ("EVENT PUSH: %s:%s connected" %(self.addr[0], str(self.addr[1])))
            modifyConfig('software.status.last_device_access', int(time.time()))
            self.t0 = threading.Thread(target=event_counting_thread, args=(self.conn, ))
            self.t0.start()

        self.s.close()
        print ("stopping Event Counting")
        log.info ("stopping Event Counting")



if __name__ == '__main__':
    # test()
    te = ThEventCounting()
    te.start()
    while True:
        print (te, te.is_alive())
        if not te.is_alive():
            te.start()
        time.sleep(30)