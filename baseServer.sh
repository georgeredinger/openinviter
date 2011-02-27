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
