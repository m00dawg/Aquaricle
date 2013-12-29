#!/bin/bash
ID=1
cd /home/tim/git/aquaricle/rrd
rrdtool graph ../static/graphs/$ID.png \
-t "Aquarium Temperature" \
-w 1280 -h 480 \
-E \
DEF:temperature=$ID.rrd:temperature:AVERAGE \
DEF:heater=$ID.rrd:heater:AVERAGE \
DEF:light=$ID.rrd:light:AVERAGE \
CDEF:weightedHeater=heater,20,* \
CDEF:weightedLight=light,20,* \
VDEF:lastTemperature=temperature,LAST \
VDEF:avgTemperature=temperature,AVERAGE \
LINE3:temperature#FF0000:"Temperature" \
GPRINT:lastTemperature:"Current\: %2.2lfC" \
GPRINT:avgTemperature:" Average\: %2.2lfC\l" \
AREA:heater#FFFF00:"Heater\l":STACK \
AREA:light#AAAAFF:"Light\l":STACK

