#!/bin/sh

LOGGER="logger -t report "

TEMPERATURE=`/bin/vcgencmd measure_temp`
MEMINFO=`/bin/free -h | /bin/grep Mem`
MEMINFOSWAP=`/bin/free -h | /bin/grep Swap`
ETH0LINK=`/sbin/ethtool eth0 2> /dev/null | /bin/grep "Link detected:" `
ETH0SPEED=`/sbin/ethtool eth0 2> /dev/null | /bin/grep "Speed:" `
ETH0DUPLEX=`/sbin/ethtool eth0 2> /dev/null | /bin/grep "Duplex:" `

$LOGGER "=====REPORT====="
$LOGGER $TEMPERATURE
$LOGGER $MEMINFO
$LOGGER $MEMINFOSWAP
$LOGGER $ETH0LINK
$LOGGER $ETH0SPEED
$LOGGER $ETH0DUPLEX
$LOGGER "===== END ======"

exit 0