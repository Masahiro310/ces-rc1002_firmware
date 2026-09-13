#!/bin/sh

LOGGER="logger -t reboot "

$LOGGER "Unit Reboot"

sudo /usr/bin/dotnet /opt/piLab/StartUpMessage8.dll /opt/piLab/message/reboot_message.txt > /dev/null
/usr/bin/sleep 1
sudo reboot

exit 0
