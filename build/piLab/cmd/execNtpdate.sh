#!/bin/bash

function valid_ip () {
    local  ip=$1
    local  stat=1

    if [[ $ip =~ ^[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}$ ]]; then
        OIFS=$IFS
        IFS='.'
        ip=($ip)
        IFS=$OIFS
        [[ ${ip[0]} -le 255 && ${ip[1]} -le 255 
            && ${ip[2]} -le 255 && ${ip[3]} -le 255 ]]
        stat=$?
    fi
    return $stat
}

LOGGER="logger -t ntpdate "
BASE_PATH="/opt/piLab/config"
NTPSETTING_FILE_NAME=$BASE_PATH/NtpSetting.xml
NTPRESULT_FILE_NAME=$BASE_PATH/ntpStatus.txt
INTERFACE_FILE_NAME=$BASE_PATH/interfaces

used_xml=`echo "cat /UnitSetting/UseNTP" | xmllint --shell $NTPSETTING_FILE_NAME`
used=`echo ${used_xml} | sed -e "s/^.*<UseNTP>\(.*\)<\/UseNTP>.*$/\1/"`

if [ ${used} != "true" ]; then
	echo "" > $NTPRESULT_FILE_NAME
	/sbin/hwclock -s > /dev/null
	if [ $? != 0 ]; then
		$LOGGER "hwclock(Read) error"
	else
		$LOGGER "set the system time from the RTC"
	fi
	exit 1
fi

echo "取得中" > $NTPRESULT_FILE_NAME
server_xml=`echo "cat /UnitSetting/NTPServer" | xmllint --shell $NTPSETTING_FILE_NAME`
server=`echo ${server_xml} | sed -e "s/^.*<NTPServer>\(.*\)<\/NTPServer>.*$/\1/"`
serverEmp=`echo ${server_xml} | awk '/<NTPServer\/>/ {print $1}'`

if [ ! -z ${serverEmp} ]; then
	echo "NTPサーバー未設定" > $NTPRESULT_FILE_NAME
	$LOGGER "NTPサーバー未設定"
	exit 1
fi
if ! valid_ip $server; then
nslookup $server > /dev/null
if [ $? != 0 ]; then
	dnsserver=`cat $INTERFACE_FILE_NAME | awk '/dns-nameservers/ {print $2}'`
	if [ $dnsserver = "127.0.0.1" ]; then
		result="DNSサーバー未設定"
	else
		result="DNS名前解決失敗"
	fi
	echo $result > $NTPRESULT_FILE_NAME
	$LOGGER $result
	exit 1
fi
fi

#step mode
sudo ntpdate -b $server > /dev/null
if [ $? != 0 ]; then
	result="NTPサーバー接続失敗"
else
	result="取得成功"
fi
echo $result > $NTPRESULT_FILE_NAME
$LOGGER $result

if [ $result != "取得成功" ]; then
	exit 1
fi

sudo hwclock -w &> /dev/null
if [ $? != 0 ]; then
	$LOGGER "hwclock error"
	exit 1
else
	$LOGGER "set the RTC from the system time"
fi

exit 0
