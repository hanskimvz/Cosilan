from functions import (_PLATFORM, configVars, _SERVER, dbconMaster)

print (_SERVER)
print (_PLATFORM)

dbConn =  dbconMaster(host='localhost', user = 'rt_user', password = '13579',  charset = 'utf8')
print (dbConn)

if not dbConn :
    print("Update server  connection Error")
    exit()
with dbConn:
    cur = dbConn.cursor()
    sq = "select pk, code, category, ldate, version_web, version_bin, flag, comment  from cosilanStatus.sw_update order by code desc"
    cur.execute(sq)
    rs = cur.fetchall()
    print(rs)
    
    sq ="update cosilanStatus.sw_update set ldate = now()"
    cur.execute(sq)
    dbConn.commit()

    sq = "select pk, code, category, ldate, version_web, version_bin,flag, comment  from cosilanStatus.sw_update order by code desc"
    cur.execute(sq)
    rs = cur.fetchall()
    print(rs)

