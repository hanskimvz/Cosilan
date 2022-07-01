import os
import sys
from chkLic import chkLicMachine, getMac, chkLic
change_log = """
2021-03-29, start on boot bug fix


"""


if os.name == "nt":
    import winreg


mode = None
try:
    mode = sys.argv[1]
    code = sys.argv[2]
except IndexError as e:
    code = '0'
    # print (e)
    # sys.exit()


if not (mode and code):
    print("""
        function4php {mode} {args}
        mode = chkLic:
            args => License code
        mode = startOnBoot : operating system will be windows and auto start on system boot
            args = yes / no
    """)
    sys.exit()

if mode == 'chkLic':
    lic_st = chkLicMachine(code)
    print("%d, %s, %s" % (lic_st[0], lic_st[1], lic_st[2]))
    sys.exit()

elif mode == 'chkLicMac':
    lic_st = chkLic(sys.argv[2], sys.argv[3])
    print("%d, %s, %s" % (lic_st[0], lic_st[1], lic_st[2]))
    sys.exit()


elif mode == 'startOnBoot':
    if os.name == 'nt':
        def register_auto_start(flag):
            execdir = os.path.dirname(os.path.abspath(sys.argv[0]))
            # file_ex = '"%s/StartBI.exe" ' %(execdir)
            # file_ex = '"%s\\python3.exe %s\\update.py start" ' %(execdir, execdir)
            file_ex = '"%s\\start.bat" ' % (execdir)
            key = winreg.OpenKey(
                winreg.HKEY_CURRENT_USER, 'Software\Microsoft\Windows\CurrentVersion\Run', 0, winreg.KEY_SET_VALUE)
            if flag == 'yes':
                winreg.SetValueEx(key, 'startBI', 0, winreg.REG_SZ, file_ex)
                print("register to auto start up")
            else:
                try:
                    winreg.DeleteValue(key, 'startBI')
                except:
                    pass
                print("cancel from auto start up")

            key.Close()

        register_auto_start(code)
