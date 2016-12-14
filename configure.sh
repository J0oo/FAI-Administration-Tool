#!/bin/bash

# 14.12.2016
echo "#####################################################################"
echo "Configure FAI-Administration-Tool"
echo "#####################################################################"

# install necessaray packages
apt-get install apache2 libapache2-mod-php5 php5 php5-common php5-json php5-ldap php5-mcrypt php5-mysql mysql-server sudo

# restart apache2
systemctl restart apache2

# move frontend to webserver directory
echo "#####################################################################"
echo "Copy files to webserver..."

echo -ne "Do you want to delete the existing index.html? [Y/N] "
read answer

if [ "$answer" = "Y" ]
then 
	rm /var/www/html/index.html
fi

cp -r frontend/* /var/www/html/

echo -ne "IP of FAI-Server: "
read fai_server

nfsroot="/srv/fai/"
echo "Default nfsroot path: /srv/fai/nfsroot"
#echo -ne "Path to nfsroot [default: /srv/fai/]: "
#read nfsroot

sed -i -e "s|%FAI_SERVER|$fai_server|" /var/www/html/javascript/control.js
sed -i -e "s|%FAI_SERVER|$fai_server|" /var/www/html/templates/pxe_conf
sed -i -e "s|%PATH|$nfsroot|" /var/www/html/templates/pxe_conf

# set permissions for webserver
chown -R www-data:www-data /var/www/html/

# configure mysql
echo "#####################################################################"
echo "Configure MySQL..."

echo -ne "MySQL Root Password: "
read -s mysqlpass
echo ""

# set standard scheme and tables for fai
echo -ne "Do you want to load the example database? [Y/N] "
read answer

if [ "$answer" = "Y" ] 
then
        mysql --user=root --password=$mysqlpass mysql <<EOF
	CREATE SCHEMA fai;
EOF

	mysql --user=root --password=$mysqlpass fai < fai_dump.sql

	mysql --user=root --password=$mysqlpass mysql <<EOF
        grant usage on *.* to fai_user@localhost identified by 'fai';
        grant all privileges on fai.* to fai_user@localhost;
   	quit
EOF
fi

# create group for fai
echo "#####################################################################"
groupadd fai
#useradd fai

# add user fai and webserver to this group
#usermod -aG fai fai
usermod -aG fai www-data

# set group permissions for necessary directories
chgrp fai /srv/tftp/fai/pxelinux.cfg
chmod g+w /srv/tftp/fai/pxelinux.cfg
chgrp fai /srv/fai/config/files/etc/network/interfaces
chmod g+w /srv/fai/config/files/etc/network/interfaces
chgrp fai /srv/fai/config/files/etc/hosts
chmod g+w /srv/fai/config/files/etc/hosts
chgrp fai /srv/fai/config/class
chmod g+w /srv/fai/config/class

echo "#####################################################################"
echo "Finally please set sudo permission for webserver to start fai-monitor-log"
echo "Look at README"
echo ""
