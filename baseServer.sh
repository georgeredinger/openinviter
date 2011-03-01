#!/bin/bash

if [ -w "/etc/hosts" ]; then
    echo "running as root"
else
  echo "Please run as root if you want to use this tool"
  echo ""
  echo "run the following command: sudo $0"
  echo ""
  exit
fi

apt-get update
apt-get upgrade
apt-get -y install php5-dev php5-cli php-pear php5-mcrypt php5-curl php5-xsl php5-mysql php5-sqlite
apt-get -y install apache2 libapache2-mod-php5

ln -s /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled/rewrite.load
ln -s /etc/apache2/mods-available/vhost_alias.load /etc/apache2/mods-enabled/vhost_alias.load

cp /var/www/openinviter/brs-api.conf.template /etc/apache2/sites-enabled
g
mkdir -p /var/www/logs
chown -R :www-data /var/www

/etc/init.d/apache2 restart
