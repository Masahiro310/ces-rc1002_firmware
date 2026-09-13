#! /bin/bash

LOGGER="logger -t mount_usb "
$LOGGER "mount $1 /media/usb1/"
sudo /bin/mount $1 /media/usb1/