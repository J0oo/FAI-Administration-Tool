# FAI-Administration-Tool
The project is based on the FAI (*Fully Automatic Installation*) project from Thomas Lange. FAI is a non-interactive system to install, customize and manage Linux operating-systems and software configurations on computers. For more information please visit [fai-project.org](http://fai-project.org/).

The FAI-Administration-Tool is a simple web based application used to create client configurations for boot over PXE and afterwards installation with FAI. It has been developed with PHP, HTML, CSS, JavaScript and MySQL. For the design I used the CSS-Framework Bootstrap [getbootstrap.com](http://getbootstrap.com/).

This project is just a test development. The following instructions and installation scripts are only tested with the Debian 8 release.

The FAI-Administration-Tool is free software, distributed under the terms of the GNU General Public License, version 3. There is no warranty, expressed or implied, associated with this product. Use at your own risk.

# What it does
This project has been developed to simplify the use of FAI. The main functionality is to create some client configurations for PXE boot and configurations for installation with FAI. Moreover, you can monitor the installation over a frontend, which shows the current states during installation. The monitoring is based on the tool *fai-monitor*.

# Prerequesite
FAI packages from [fai-project.org](http://fai-project.org/) must be installed on the system. Furthermore the directory for PXE configurations must be on */srv/tftp/fai/pxelinux.cfg* and the nfsroot directory on */srv/fai/nfsroot*.
If not, you must change the right paths in all files.

The frontend gets the Bootstrap files from a CDN. You also can download and host Bootstrap by yourself. For this you must change the link and sources in the *header.php* and the link in the *index.php*.

# Installation
There is a script, *configure.sh*, that installs all missing packages for this tool (without FAI packages) and configures the environment. Use the script at your own risk, be careful. Executing the script leads to the following changes:
 1. It installs the webserver Apache, some PHP packages, the database MySQL and sudo.
  * Package names: apache2 libapache2-mod-php5 php5 php5-common php5-json php5-ldap php5-mcrypt php5-mysql mysql-server sudo*
 2. It removes the *index.html* in */var/www/html/* and copy the frontend files to */var/www/html*.
 3. If desired, it load a basic mysql-dump to the database.
 4. It adds a new group called *fai* and add the webserver user to it.
 5. It change permissions for the following directory to the group *fai*:
  * */srv/tftp/fai/pxelinux.cfg*
  * */srv/fai/config/files/etc/network/interfaces*
  * */srv/fai/config/files/etc/hosts*
  * */srv/fai/config/class*
  
For more information look at *configure.sh*. In case you are unsure whether you should use the script, you can of course perform the entire configuration by hand and step by step and only with the settings you want.

# Follow-up
To allow the webserver user to start *fai-monitor*, you must add the following line to */etc/sudoers* (use visudo):
* *www-data ALL=(root) NOPASSWD: /usr/sbin/fai-monitor*

# Optional
It is possible to use LDAP to login to the frontend. You can edit the LDAP settings in *login.php*. If you don't want to use LDAP you can login without using a name or password.

# Manual
## Formular view
On this side, you can create the client configurations.
Note: The FAI-Classes you can add, are only the classes that won’t be added dynamically during installation. Currently the choice of a dynamic IP assignment doesn’t create a network and host configuration.

## Class view
This is an overview of all classes in your database with a short description.

## Monitoring view
The monitoring view gives you feedback about the current state during the installation. With AJAX the states are continuously updated. This side is based on the tool *fai-monitor*. Furthermore, you see whether *fai-monitor* is running or not.

## Installclients
This view represents a table of all created client configurations.
Note: The classes shown in this table are only the ones that have been added in the formular view (meaning: not dynamically added).
