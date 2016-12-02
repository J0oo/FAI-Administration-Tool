# FAI-Frontend
This is a simple frontend to create configurations for boot over PXE and install with FAI. This project is using the project from Thomas Lange *Fully Automatic Installation*. For more information show [fai-project.org](http://fai-project.org/).

This Frontend is just a test development. The following instructions and installation script are only tested on Debian systems.

# What it does
This frontend is used to create client configurations for PXE boot and installation with FAI. It creates a PXE configuration file pxelinux.cfg, a network and host configuration file and optional a new password to the class configuration. Furhtermore, you can manage a overview for FAI classes. Moreover, there is a monitoring page, which shows the current states of installation. 

# Prerequesite
FAI packages from fai-project.org must be installed on the system. Furthermore the directory for PXE configurations must be on */srv/tftp/fai/pxelinux.cfg.*

# Installation
There is a script *configure.sh* that installs all missing packages and configure the environment. Be careful for using this script. 
The script does following settings:
 1. It installs the webserver Apache, some PHP packages, the database MySQL and sudo.
  * Package names: apache2 libapache2-mod-php5 php5 php5-common php5-json php5-ldap php5-mcrypt php5-mysql mysql-server sudo
 2. It removes the index.html in */var/www/html/* and copy the frontend-files to */var/www/html*.
 3. If desired, it load a basic mysql-dump to the database.
 4. It adds a new group called *fai* and add the webserver user to it.
 5. It change permissions for the following directory to the group *fai*:
  * */srv/tftp/fai/pxelinux.cfg*
  * */srv/fai/config/files/etc/network/interfaces*
  * */srv/fai/config/files/etc/hosts*
  * */srv/fai/config/class*
  
For more informations look at *configure.sh*.

# Follow-up
To allow the webserver user to start fai-monitor, you must add following line to */etc/sudoers* (use visudo):
* *www-data ALL=(root) NOPASSWD: /usr/sbin/fai-monitor*

# Optional
It is possible to use LDAP for login to the frontend. You can edit the LDAP settings in *login.php*. If you don't want use LDAP you can login immediately without using a name or password.

# Database 

# Manual

