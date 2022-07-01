
import py_compile
  
c = py_compile.compile('chkLic_s.py', 'chkLic.pyc')
print(c)

c = py_compile.compile('functions_s.py', 'functions.pyc')
print(c)

c = py_compile.compile('function4php.py', 'function4php.pyc')
print(c)

# py_compile.compile('counting_main_s.py', 'counting_main.pyc')
# print (c)

# py_compile.compile('face_det_s.py', 'face_det.py')
# print (c)

# py_compile.compile('proc_db_s.py', 'proc_db.py')
# print (c)
