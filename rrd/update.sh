#!/bin/bash

ID=1
HOST='aquariduino.moocow.home'
VALUES=`curl -s aquariduino.moocow.home`
TEMP=`php temp.php`
HEATER=`php heater.php`
LIGHT=`php light.php`
#TEMP=`echo -n "$VALUES" | grep "Temp"  | awk -F: {'print $2'}`
#HEATER=`echo -n "$VALUES" | grep "Heater" | awk -F: {'print $2'}`
#LIGHT=`echo -n "$VALUES" | grep "Light" | awk -F: {'print $2'}`
cd /home/tim/git/aquaricle/rrd
rrdtool update $ID.rrd N:$TEMP:$HEATER:$LIGHT
