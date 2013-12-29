#!/bin/bash

# Step: 300 seconds
# First RRA: 1 Year
#   1 sample = 300 seconds
#   1 Year = 31536000 seconds
#   31536000 seconds = 105120 samples
# Second RRA: 5 Years
#   12 samples = 3600 seconds = 1 hour
#   8760 hours = 365 days
#   43800 hours = 5 years
ID=1
rrdtool create $ID.rrd --step 300 \
DS:temperature:GAUGE:900:0:100 \
DS:heater:GAUGE:900:0:1 \
DS:light:GAUGE:900:0:1 \
RRA:AVERAGE:0.5:1:105120 \
RRA:AVERAGE:0.5:12:43800 
