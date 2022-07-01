import os, sys
from chkLic import genLic


mac = input('MAC: ')
mac = mac.replace(':','')
mac = mac.replace('-','')

datetime = input('Exprire Date: ')

lic_code = genLic(mac, datetime)

print (lic_code)
