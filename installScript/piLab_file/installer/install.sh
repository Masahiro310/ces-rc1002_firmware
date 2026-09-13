#!/bin/sh
# cesrc1002 Install process Manager

LOGGER="logger -t cesrc1002_wakeup "

/usr/bin/touch /opt/installer/WAITTING_INIT
/usr/bin/gpioset gpiochip0 18=1
/bin/echo ds1307 0x68 > /sys/class/i2c-adapter/i2c-1/new_device

/sbin/hwclock -s > /dev/null
if [ $? != 0 ]; then
	/sbin/hwclock -w
	$LOGGER "set the RTC from the system time"
else
	$LOGGER "set the system time from the RTC"
fi

/bin/bash /opt/piLab/cmd/execNtpdate.sh

/usr/bin/dotnet /opt/installer/StartUpMessage8.dll
if [ -e /opt/installer/INSTALLCESRC1002 ]; then
	. /opt/installer/cesrc1002/cesrc1002-install.sh
	cesrc1002_install
	/bin/rm /opt/installer/INSTALLCESRC1002
fi
/bin/sleep 3
/bin/rm /opt/installer/WAITTING_INIT
