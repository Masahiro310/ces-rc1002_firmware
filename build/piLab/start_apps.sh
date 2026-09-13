#!/bin/bash
logger "remount(ro) /boot"
/bin/sudo /bin/mount -o remount,ro /boot

logger "start mono"
while [ -e /opt/installer/WAITTING_INIT ]
do
	sleep 1
done

if [ -e /opt/piLab/reboot.txt ]; then
	rm -f /opt/piLab/reboot.txt
	/opt/piLab/cmd/reboot.sh
fi
/bin/sudo /bin/chown www-data:www-data /opt/piLab/config/*.txt
/bin/sudo /bin/chmod 666 /opt/piLab/config/*.txt

#/bin/python /opt/piLab/configGpio.py
/bin/sh /opt/piLab/setgpio_file.sh
/usr/bin/gpioset gpiochip0 18=1
/bin/sh /opt/piLab/cmd/getRaspiReport.sh
/usr/bin/dotnet /opt/piLab/CES_RC1002.dll /opt/piLab/config/Config.xml
#/bin/sudo -u pi /bin/vlc --fullscreen rtsp://192.168.0.226/0
