#!/bin/sh

LOGGER="logger -t hwclock "

sudo hwclock -w &> /dev/null
if [ $? != 0 ]; then
	$LOGGER "hwclock error"
	exit 1
else
	$LOGGER "set the RTC from the system time"
fi

exit 0
