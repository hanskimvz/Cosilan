
# Release Note

## Ver 0.96 Beta
#### Realtime screen
> 1. Realtime screen  changes with bin/rtCounting.py, bin/rt_main.py
> 2. Realtime screen doesn't need operate with Server side.
> 3. support template, template doc starts with "template" as template1, template2 

#### Update function
> 1. update 0.95 to 0.96
> 2. Python 3.8.3 to 3.8.8, with patch.py and update_main
> 3. include module opencv, tkinter, PIL, psutil.

### HTML
#### pubSVC.php
> getsnapshot command, include device info query

Todo:
> realtime screen : streaming video

----
## Ver 0.95 Beta
### Cosilan binary
> db_function for database functions.

#### proc_db : 
> 2022-03-28, V0.95, sum up 3 functions (facedb, countdb, heatmapdb) to one procdb.

#### Functions 
> 2021-04-18, browse device with arp, because many sites do not support upnp
> 2021-05-04, config file, config db file path -> os.path.dirname(sys.argv[0])
> 2021-05-23, check lic.
> 2021-08-10, support only param, CFG -> configVars and logging file to file only
> 2021-08-15, License, param parametr=> software.status.xxxx
> 2022-03-18, local lan, use requests module instead of HTTPConnection because of Digest auth
> 2022-04-03, add function :info_from_db
> 2022-04-03, add option using optparse module

#### Monitor.py
> 2022-03-30, communication  startBI via TCP localhost

### proc_event.py
> 2022-03-24, seperate from counting_main.py
> 2022-03-26, always actived Event Counting, it doesnt matter whether TCP, HTTP. TCP mode starts with DOOFTEN~, it's like parsing problem.
> 2022-03-27, Even if Face detection with HTTP POST, it can handle it.


#### Event count
> 2022-03-26, always actived Event Counting, it doesnt matter whether TCP, HTTP. TCP mode starts with DOOFTEN~, it's like parsing problem.

#### Rt screen
> 2021-04-16, version 0.9, first

#### Sys admin daemon
> 2022-03-27, system configuration and report. backup, update, etc.

#### startbi.py
> 2022-03-26, version 0.95. sperate program.. tlss_counting.py, active_counting.py, event_counting.py, parse_functions.py, db_functions.py, cgis.py
> 2022-03-30, version 0.95, reapeated thread like db program will use threading.Timer instead of while, sleep

#### tlss_counting.py
> 2022-03-26, counting_main to (tlss_counting, active_counting and proc_event)

#### active counting.py
> 2022-03-24, seperate from counting_main.py
> 2022-03-24, only for active counting, snapshot, heatmap etc. direct access to device and get data.
> 2022-03-24, since 0.9.5 and date from 

#### Html


----
## Ver 0.94
### Cosilan Binary
#### Functions py
> 2021-02-24, function parseX... added
> 2021-02-25, bug fix: parseParam returns dict when error, return Fase -> return dict_rs
> 2021-02-26, pymysql with 'with' statement, pymysql version should be above 1.0


#### Update function
> 2021-05-06, initial programming.


#### Monitor.py
> 2021-08-14, monitor.py with windows, \\bin\\

#### startbi.py
> 2021-12-25, version 0.94, support both TLSSand  ACTIVE.
> 2021-12-26, version 0.94, Auto Update, Auto Backup.

#### tlss_counting.py
2021-12-12, params-> manual, auto


----
## V0.93
### Coslian Binary
> support configVars not CFG

#### startbi.py
> 2021-08-11. version 0.93, support python binary in windows mode.
> 2021-09-09, version 0.93, thread stop when network is not linked.

#### tlss_counting.py
> 2021-04-09, In some PC  environment, (?,?~) cannot be used -> (%,%~)
> 2021-04-09, Function write_param(conn, device_info)
> 2021-05-04, program halt when network unstable, add try method
> 2021-08-10, V0.93. support only sqlite param file, so CFG=>configVars


----
## V0.92
#### Coslian Binary
##### Procdb.py
> only for python3.
> fix bug, update counting db via db_name, if db_names are many

##### functions.py
> 2021-01-25, config from file or sqlite, log to file or sqlite 
> 2021-01-27, modify architechure and create table info_tbl, and function info_to_db
> 2021-02-16, modify CFG in sqlite functions, realtime update when modify setting.
> 2021-02-16, only python3, elimate python2 code

##### monitor.py
2021-03-24, windows program and python3 only

#### startbi.py
> 2021-02-25, version 0.9, upnp server(linux) is embedded in system, eleminate it
> 2021-04-04, version 0.9, os.chdir(absdir) => when service program restart, change working directory to abs dir to read config_db_file

#### tlss_counting.py
> 2021-02-17, only for python3, erase python2 code
> 2021-02-17, connecting pymysql -> with, executemany 


----
### V0.91
### Coslian Binary
#### Proc db:
> 2020-11-24, thread class, set daemon

### V0.90
#### Coslian Binary
##### Proc db: 
> Mainly common database to user(cnt_demo) database
2020-06-12, data read from common database (counting_report_10min, face_det, MacSniff, param) etc and store to custom database like cnt_demo, custom_database, SH_TEST etc.
2020-09-08, Python 2.7 to Python 3.8 starting on 2020-09-08 
2020-11-16, Because of Active mode, and merge start BI, seperate several thread 

#### functions.pyc
> 2020-10-25, chkLic.py-> chkLic_s.py and compile this into chkLic.pyc
> 2020-10-25, functions.py-> functions_s_s.py and compile this into functions.pyc
> 2020-11-25, if Invalid lic, running 3hours and exit

#### monitor.py
> 2020-11-20, build 100

#### startbi.py
> Start BI, management Nginx, php, mysqld , counting_main(thActive, TLSS, thEvent), proc_db(3 database tables)
> 2020-11-26, Active mode (local lan) added
> 2020-11-17, version 0.9, build 099 
> 2020-11-18, version 0.9, build 100 
> 2020-11-19, version 0.9, build 101 
> 2020-11-20, version 0.9, build 102 : counting , thread->normal function(que)
> 2020-11-26, version 0.9, build 103 : counting , thread function-> class from module
> 2020-12-25, version 0.9, build 104 : startBI, excute_command()
> 2020-12-27, version 0.9, build 105 : browse device service (service provider) with upnp server, upnp_server.py

#### tlss_counting.py
> 2020-12-25, version 0.9, build 104 : query_countingreport, return if No Data
> 2020-12-26, service for device to common database, 
> 2021-01-27, added import 'info_to_db'


