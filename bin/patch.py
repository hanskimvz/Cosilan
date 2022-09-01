# Copyright (c) 2022, Hans kim

# Redistribution and use in source and binary forms, with or without
# modification, are permitted provided that the following conditions are met:
# 1. Redistributions of source code must retain the above copyright
# notice, this list of conditions and the following disclaimer.
# 2. Redistributions in binary form must reproduce the above copyright
# notice, this list of conditions and the following disclaimer in the
# documentation and/or other materials provided with the distribution.

# THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND
# CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES,
# INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
# MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
# DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR
# CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
# SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
# BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
# SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
# INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
# WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
# NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
# OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

import os, sys, time, zipfile, shutil
from http.client import HTTPConnection


### update python 3.8.8. if need
def checkPythonBin():
    # True : need to download, False: no need to download
    if os.name != 'nt':
        print ("This function is only for windows operationg system")
        return False
    if os.name == 'nt':
        import winreg
        import zipfile
        try:
            import psutil
            import cv2
            import PIL
        except Exception as e:
            print(e)
            return True
    
    pv = sys.version_info
    if not (int(pv.major >=3) and int(pv.minor) >=8 and int(pv.micro) >=8):
        return True
    return False

def downloadPatchTool():
    import zipfile
    global _ROOT_DIR, _SERVER_IP, _SERVER_PORT
    target_dir = "%s\\bin\\patch\\" %_ROOT_DIR
    if not os.path.isdir(target_dir):
        os.mkdir(target_dir)
    os.chdir(target_dir)
    fname = "patchtool_python.zip"
    server = (_SERVER_IP, _SERVER_PORT)
    conn = HTTPConnection(*server)
    print ("downloading")
    conn.putrequest("GET", "/download.php?file=%s" %fname) 
    conn.endheaders()
    rs = conn.getresponse()
    with open(fname, "wb")  as f:
        f.write(rs.read()) 
               
    conn.putrequest("GET", "/download.php?file=../bin/patch.py") 
    conn.endheaders()
    rs = conn.getresponse()
    with open("patch.py", "wb")  as f:
        f.write(rs.read())        

    print ("downloaded")

    zf = zipfile.ZipFile(fname,'r')
    for fname in zf.namelist():
        try:
            zf.extract(fname, target_dir)
        except Exception as e:
            print (e)


def downloadNewPython():
    global _ROOT_DIR, _SERVER_IP, _SERVER_PORT
    fname = "python_bin_096_basic.zip"
    os.chdir("%s\\bin\\patch" %(_ROOT_DIR))
    print ("downloading, file size is about 80MB please wait...")
    cmdstr = "wget http://%s:%d/download.php?file=%s -O %s" %(_SERVER_IP, _SERVER_PORT, fname, fname )
    os.system(cmdstr)
    print ("downloaded")

def deleteOldFiles():
    os.chdir("%s\\bin" %(_ROOT_DIR))
    for fname in os.listdir(".\\"):
        if fname.endswith(".py"):
            continue
        if fname.startswith("param"):
            continue
        if fname == "log" or fname == "patch":
            continue
        print(fname) 
        # print (x)
        if os.path.isdir(fname):
            shutil.rmtree(fname)
        elif os.path.isfile(fname):
            os.remove(fname)



if __name__ == '__main__':
    _ROOT_DIR = os.path.dirname(os.path.dirname(os.path.dirname(os.path.abspath(sys.argv[0]))))
                
    _SERVER_IP = '49.235.119.5'
    _SERVER_MAC = "525400C9FE37"
    _SERVER_PORT = 80    

    # kill parent process
    if len(sys.argv) > 1:
        kill_pid = sys.argv[1] 
        os.system("taskkill /pid %d /F" %(int(kill_pid))) 


    os.chdir("%s\\bin\\patch" %(_ROOT_DIR))
    # downloadNewPython()


    # delete old python files
    time.sleep(2)
    deleteOldFiles()

    # extract new python files
    os.chdir("%s\\bin\\patch" %(_ROOT_DIR))
    fname = "python_bin_096_basic.zip"
    print ("extracting...")
    extract_path = "%s\\bin\\" %_ROOT_DIR
    zf = zipfile.ZipFile(fname,'r')
    for fname in zf.namelist():
        print (fname)
        try:
            zf.extract(fname, extract_path)
        except Exception as e:
            print (str(e))
    zf.close()


    