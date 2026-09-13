#!/bin/sh

LOGGER="logger -t CheckDupAddress "

IPCOUNT=`/usr/bin/arping -D -c 2 -I $1 $2 | /bin/grep "Received" | /usr/bin/cut -d ' ' -f2`

if [ $IPCOUNT = 0 ]; then
    $LOGGER "NotFound $2"
    echo "no"
    exit 0
fi
# found dup address

ARPINGLOG=`/usr/bin/arping -D -c 2 -I $1 $2`
$LOGGER $ARPINGLOG
echo "yes"
exit 1
