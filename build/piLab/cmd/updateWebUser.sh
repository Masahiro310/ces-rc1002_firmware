# /bin/sh

logger "call updateWebUser.sh"

BASE_PATH="/opt/piLab"

UNITSETTING_FILE_NAME=$BASE_PATH/config/UnitSetting.xml
unit_xml=`echo "cat /UnitSetting/WebPassword" | xmllint --shell $UNITSETTING_FILE_NAME`
password=`echo ${unit_xml} | sed -e "s/^.*<WebPassword>\(.*\)<\/WebPassword>.*$/\1/"`

unit_xml=`echo "cat /UnitSetting/WebUser" | xmllint --shell $UNITSETTING_FILE_NAME`
user=`echo ${unit_xml} | sed -e "s/^.*<WebUser>\(.*\)<\/WebUser>.*$/\1/"`

user=`echo ${user} | sed -e 's/&lt;/</g'`
user=`echo ${user} | sed -e 's/&gt;/>/g'`

password=`echo ${password} | sed -e 's/&lt;/</g'`
password=`echo ${password} | sed -e 's/&gt;/>/g'`

/usr/bin/htpasswd -c -b $BASE_PATH/www/.htpasswd "$user" "$password"

