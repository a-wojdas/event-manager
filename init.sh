#!/bin/bash

HTTPDUSER=`ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`

sudo rm -rf /tmp/test01/*

sudo mkdir /tmp/test01
sudo mkdir /tmp/test01/cache
sudo mkdir /tmp/test01/logs
sudo mkdir /tmp/test01/sessions

sudo chmod 0777 -R /tmp/test01
sudo chmod 0777 -R /tmp/test01/cache
sudo chmod 0777 -R /tmp/test01/logs
sudo chmod 0777 -R /tmp/test01/sessions

sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX /tmp/test01/cache /tmp/test01/logs /tmp/test01/sessions
sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:`whoami`:rwX /tmp/test01/cache /tmp/test01/logs /tmp/test01/sessions


#HTTPDUSER=`ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1`
#sudo chmod +a "$HTTPDUSER allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs
#sudo chmod +a "`whoami` allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs
