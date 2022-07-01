# import os, time, sys
# from http.client import HTTPConnection
# from urllib.parse import urlparse, parse_qsl, unquote
# import socket
# import re, base64, struct
# import threading
from functions import (dbconMaster)

def deleteDuplicate(db_name):
    dbconn = dbconMaster()
    print(dbconn)
    with dbconn:
        cur = dbconn.cursor()    
        sq = "select * from " + db_name +".count_tenmin  order by timestamp, counter_name, counter_label "
        print (sq)
        cur.execute(sq)
        rows = cur.fetchall()
        prev = []
        pks = ""
        i=0
        for row in rows:
            # print(row)
            if prev[1:] == row[1:]:
                print(prev)
                print(row)
                print()
                if(pks) :
                    pks += " or "
                pks += "pk=" + str(prev[0])
                i+=1
                
            prev = row

        if(pks):
            sq = "delete from " + db_name +".count_tenmin where " + pks
            print (sq)
            # cur.execute(sq)
            # dbconn.commit()


    



deleteDuplicate('cnt_demo')
