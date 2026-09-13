#!/bin/sh

/sbin/ethtool $1 2> /dev/null | /bin/grep "Link detected:" | /usr/bin/awk -F ": " '{print $2}'

exit 0
