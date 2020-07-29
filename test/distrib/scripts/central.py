import tkinter as tk
from tkinter import simpledialog
import os
from os.path import expanduser
import subprocess
import pexpect
import time
from shutil import which
from netifaces import interfaces

# Checks if a string is contained in a file
# Source : https://thispointer.com/python-search-strings-in-a-file-and-get-line-numbers-of-lines-containing-the-string/#:~:text=Check%20if%20a%20string%20exists%20in%20a%20file&text=If%20the%20line%20contains%20the,string%2C%20then%20it%20returns%20False.&text=As%20file%20contains%20the%20'is,function%20check_if_string_in_file()%20returns%20True.
def checkStrInFile(filename, strToFind):
    with open(filename, 'r') as read_obj:
        for line in read_obj:
            if strToFind in line:
                return True
    return False

# Checks if the command is installed
def ls_command(name):
    return which(name) is not None

# Checks if a wifi interface is present
def checkInt():
    ifaces = "ip link show | grep w | cut -d \" \" -f 2 | rev |cut -c 2- | rev"
    wifi = subprocess.getoutput(ifaces)
    return wifi


# Checks if the user has set up a connection to a given network
def checkNet(network):
    wifi = checkInt()
    if not wifi:
        return False

    net = "nmcli connection show " + network
    process = subprocess.Popen(net.split(), stdout=subprocess.PIPE)
    isconf = False
    while True:
        output = process.stdout.readline()
        if (b'connection.id:') in output :
            isconf = True
        if output.startswith(b'') and process.poll() is not None:
            break
    rc = process.poll()
    if isconf :
        print("Connection is already set")
        return False
    else :
        net = "nmcli con add type wifi ifname " + wifi + " con-name "+ network + " ssid " + network
        process = subprocess.Popen(net.split(), stdout=subprocess.PIPE)

    time.sleep(3)
    return True


# Connects the user to a network
def connect(username, password, ext, network):
    command = "nmcli con edit id " + network
    method = "set ipv4.method auto"
    eap = "set 802-1x.eap peap"
    auth = "set 802-1x.phase2-auth mschapv2"
    mgmt = "set wifi-sec.key-mgmt wpa-eap"
    user = "set 802-1x.identity " + username + ext

    pwd = "set 802-1x.password " + password
    save = "save"
    acti = "activate"
    quit = "quit"

    child = pexpect.spawn(command)
    child.expect('nmcli>')
    child.sendline(method)

    child.expect('nmcli>')
    child.sendline(eap)

    child.expect('nmcli>')
    child.sendline(auth)

    child.expect('nmcli>')
    child.sendline(mgmt)

    child.expect('nmcli>')
    child.sendline(user)

    child.expect('nmcli>')
    child.sendline(pwd)

    child.expect('nmcli>')
    child.sendline(save)

    child.expect('nmcli>')
    child.sendline(acti)

    child.expect('Connection successfully activated')
    child.sendline()

    child.expect('nmcli>')
    child.sendline(save)

    child.expect('nmcli>')
    child.sendline(quit)

# This part is used to get a GUI prompt to enter your creds

application_window = tk.Tk()
username = simpledialog.askstring("Input", "What is your username for AAI (surname.name)", parent=application_window)
password = simpledialog.askstring("Input", "What is your password", parent=application_window, show='*')

# This part sets up the eduroam network

# TODO : integration du script eduroam-linux-HES-SO.py

if checkNet("eduroam"):
    connect(username, password, "@hes-so.ch", "eduroam")
else:
    print("no need to set up")


# This part sets up the HEIG-VD network

if checkNet("HEIG-VD"):
    connect(username, password, "@heig-vd.ch", "HEIG-VD")
else:
    print("no need to set up")

# This part sets up the VPN

file_path = expanduser("~/.zshrc")
file_object = open(file_path, 'a')
alias = "alias vpnHEIG=\"sudo openconnect --authgroup=All_Users --user=" + username + " https://remote.heig-vd.ch\""

if checkStrInFile(file_path, alias):
    print("In file")
else:
    alias = alias + "\n"
    file_object.write(alias)

# This part sets up the printers

# PPD file has to be placed in "~/driver/"
ppd_path = expanduser("~/driver/HEIG_Printer.ppd")
printer = "lpadmin -p FOLLOWME_PS -E -v smb://EINET/" + username + ":" + password + "@print.einet.ad.eivd.ch/FOLLOWME_PS -P " + ppd_path + " -L \"HEIG-VD\" -o auth-info-required=negotiate"
process = subprocess.Popen(printer.split(), stdout=subprocess.PIPE)


# This part sets up the eistore shares

eistore0 = "sudo mount -v -t cifs -o domain=EINET,username=" + username + " //eistore0/softs /mnt/eistore0"
alias0 = "alias eistore0=\"" + eistore0 + "\""
eistore1 = "sudo mount -v -t cifs -o domain=EINET,username=" + username + " //eistore1/softs / mnt/eistore0"
alias1 = "alias eistore1=\"" + eistore1 + "\""

if checkStrInFile(file_path, alias0):
    print("In File")
else:
    alias0 = alias0 + "\n"
    file_object.write(alias0)

if checkStrInFile(file_path, alias1):
    print("In File")
else:
    alias1 = alias1 + "\n"
    file_object.write(alias1)

file_object.close()

