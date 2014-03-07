#!/bin/bash
ID=1
cd /home/tim/git/aquaricle/rrd
rrdtool graph ../static/graphs/$ID-temp-thumb.png \
-t "Aquarium Temperature" \
-w 480 -h 120 \
-E \
DEF:temperature=$ID.rrd:temperature:AVERAGE \
VDEF:lastTemperature=temperature,LAST \
VDEF:avgTemperature=temperature,AVERAGE \
LINE1:temperature#FF0000:"Temperature" \
GPRINT:lastTemperature:"Current\: %2.2lfC" \
GPRINT:avgTemperature:" Average\: %2.2lfC\l"

rrdtool graph ../static/graphs/$ID-temp-full.png \
-t "Aquarium Temperature" \
-w 1280 -h 640 \
-E \
DEF:temperature=$ID.rrd:temperature:AVERAGE \
VDEF:lastTemperature=temperature,LAST \
VDEF:avgTemperature=temperature,AVERAGE \
LINE1:temperature#FF0000:"Temperature" \
GPRINT:lastTemperature:"Current\: %2.2lfC" \
GPRINT:avgTemperature:" Average\: %2.2lfC\l"

rrdtool graph ../static/graphs/$ID-relays-thumb.png \
-t "Aquarium Relays" \
-w 480 -h 120 \
-E \
DEF:heater=$ID.rrd:heater:AVERAGE \
DEF:light=$ID.rrd:light:AVERAGE \
AREA:light#AAAAFF:"Light\l":STACK \
AREA:heater#FFFF00:"Heater\l":STACK 

rrdtool graph ../static/graphs/$ID-temp-full.png \
-t "Aquarium Temperature" \
-w 1280 -h 640 \
-E \
DEF:heater=$ID.rrd:heater:AVERAGE \
DEF:light=$ID.rrd:light:AVERAGE \
AREA:light#AAAAFF:"Light\l":STACK \
AREA:heater#FFFF00:"Heater\l":STACK 
