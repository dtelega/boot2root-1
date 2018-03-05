-------------------------------------------------------------------------------

# The following method describes a Race Condition Privilege Escalation

-------------------------------------------------------------------------------

## First we need to find the IP address of the VM

* Refer to writeup1


## login
* Now we need to get access to the file system of the ISO.
* Among other files, this ISO contains the following one : "filesystem.squashfs".
* This is actually the whole compressed file system.
* To decompress and extract it we can use "squashfs".

```
unsquashfs -f -d /path/to/destination/ /path/to/source/filesystem.squashfs
```

* Directory : /home/lmezard/
-------------------------------------------------------------------------------

* In /home/lmezard/ we find 2 files.

```
cd /home/lmezard/ ; ls
fun  README
```
* The fun file is actually an archive...

```
file fun
fun: POSIX tar archive (GNU)
```

* Decompressing it produces a new directory called "ft_fun".

```
tar xvf fun ; ls
ft_fun  fun  README
```

* "ft_fun" directory contains a fuck tone of files...
* We notice that each one of them is written in C language and its last line is tagged with a comment like so
//file3
//file235
...

* We can then make a little PHP script to read them and recompose the original C file, compile the file and execute it.

```
php pack.php && gcc main.c && ./a.out
MY PASSWORD IS: Iheartpwnage
Now SHA-256 it and submit
```

* If we sha256 this password we obtain :

```330b845f32185747e4f8ca15d40ca59796035c89ea809fb5d30f4da83ecf45a4```

* We can now connect with ssh as "laurie".



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
