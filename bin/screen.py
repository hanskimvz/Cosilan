import time, random

from tkinter import *


visit_in = 1110
visit_out = 100
visit_yesterday =  92353
occupy = 0

visit_total = 7452645
def start():
    global visit_in, visit_out, visit_total, visit_yesterday
    up =  random.randint(0,9)
    down =  random.randint(0,9)
    visit_in += up
    visit_out += down
    text =  "{:,d}".format(visit_in)
    

    cntToday.after(500, start)
    if (up%2) :
        return False
    
    cntToday.config(text=text) 
    # cntYesterday.config(text="{:,d}".format(visit_yesterday))
    cntYesterday.config(text="{:,d}".format(visit_out))
    
    visit_total += visit_in
    # cntTotal.config(text="{:,d}".format(visit_total))

    visit_average = visit_total/(12*7)
    occupy = visit_in - visit_out
    cntAve.config(text="{:,d}".format(int(occupy)))
    #cntAve.config(text="{:,d}".format(int(visit_average)))

    change = up-down
    if change == 0:
        ct1.config(text= str(change), fg='blue')    
    elif change >0:
        ct1.config(text= '+' + str(change), fg='light blue')    
    else :
        ct1.config(text= str(change), fg='orange')    
    # change = '+' + (up-down) if up-down >=0 else up-down
    # ct1.config(text= str(change))



root =Tk()
pad = 3
# root.geometry("1024x600+0+0")

def stop_fullscreen(event=None):
    root.overrideredirect(False)
    root.attributes("-fullscreen", False)
    

root.geometry("%dx%d+0+0"%((root.winfo_screenwidth()), (root.winfo_screenheight()-pad)))
root.bind('<Escape>', stop_fullscreen)

root.configure(background="black")

root.resizable (0,0)




root.overrideredirect(True)
Label(root, text="000" ,font=("ds digital", 50, "bold"), fg="black", bg="black" ).grid(row=0, column=0)
Label(root, text="000000000000000000" ,font=("ds digital", 50, "bold"), fg="black", bg="black" ).grid(row=0, column=1)
Label(root, text="000" ,font=("ds digital", 50, "bold"), fg="black", bg="black" ).grid(row=0, column=2)
Label(root, text="000000000000000000" ,font=("ds digital", 50, "bold"), fg="black", bg="black" ).grid(row=0, column=3)
# Label(root, text="0000000" ,font=("ds digital", 50, "bold"), fg="light green", bg="black" ).grid(row=0, column=4)

labelToday = Label(root, font=("ds digital", 40, "bold"), fg="light green", bg="black")
labelToday.grid(row=1, column=1)
# labelToday.configure(text="  今日访问者  ")
labelToday.configure(text="  今日进场人数  ")

labelYesterday = Label(root, font=("ds digital", 40, "bold"), fg="light green", bg="black")
labelYesterday.grid(row=1, column=3)
# labelYesterday.configure(text="  昨日访问者  ")
labelYesterday.configure(text="  今日出场人数  ")

labelAve = Label(root, font=("ds digital", 40, "bold"), fg="blue", bg="black")
labelAve.grid(row=3, column=2)
# labelAve.configure(text="  12周平均访问者  ")
labelAve.configure(text="  场里总共人数  ")


# labelTotal = Label(root, font=("ds digital", 40, "bold"), fg="light green", bg="black")
# labelTotal.grid(row=3, column=3)
# labelTotal.configure(text="  12周总共访问者  ")



cntToday = Label(root, font=("ds-digital", 100, 'bold'), bg="black", fg='red', bd=50)
cntToday.grid(row=2, column=1)

cntYesterday = Label(root, font=("ds-digital", 100, 'bold'), bg="black", fg='red', bd=50)
cntYesterday.grid(row=2, column=3)

cntAve = Label(root, font=("ds-digital", 100, 'bold'), bg="black", fg='red', bd=50)
cntAve.grid(row=4, column=2)

# cntTotal = Label(root, font=("ds-digital", 100, 'bold'), bg="black", fg='red', bd=50)
# cntTotal.grid(row=4, column=3)




ct1 = Label(root, font=("ds-digital", 30, 'bold'), bg="black", fg='light blue', bd=50)
ct1.grid(row=2,column=2)

start()

print ("done")

root.mainloop()
