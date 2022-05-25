#!/bin/bash
echo "Install voipiran License Manager"
echo "VOIPIRAN.io"

###Fetch DB root PASSWORD
rootpw=$(sed -ne 's/.*mysqlrootpwd=//gp' /etc/issabel.conf)

sleep 1

echo "------------START-----------------"

#echo "Install sourceguardian Files"
#echo "------------Copy SourceGaurd-----------------"
#yes | cp -rf sourceguardian/ixed.5.4.lin /usr/lib64/php/modules
#yes | cp -rf sourceguardian/ixed.5.4ts.lin /usr/lib64/php/modules
#yes | cp -rf /etc/php.ini /etc/php-old.ini
#yes | cp -rf sourceguardian/php.ini /etc
#echo "SourceGuardian Files have Moved Sucsessfully"
sleep 1


cp -rf dialer /var/www/html/
chown -R asterisk:asterisk /var/www/html/dialer

#Change DB Password
sed -i "s/123456/${rootpw}/g" /var/www/html/dialer/db.php
sed -i "s/123456/${rootpw}/g" /var/www/html/dialer/callrequest-params.ini



sleep 1

echo "-----------FINISHED (voipiran.io)-----------"


