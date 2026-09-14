#!/bin/sh
for pinNo in  16 17 22 23 25 26 27
do
	if [ ! -d /sys/class/gpio/gpio$pinNo ]; then
		/bin/echo $pinNo > /sys/class/gpio/export
	fi 
done

