#! /bin/bash

LOGGER="logger -t unmount_usb "
$LOGGER "umount /media/usb1/"

sudo /bin/umount /media/usb1/