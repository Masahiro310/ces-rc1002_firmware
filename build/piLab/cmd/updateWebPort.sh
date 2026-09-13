# /bin/sh

logger "call updateWebPort.sh"

BASE_PATH="/opt/piLab"

PORT_TXT_FILE_NAME=$BASE_PATH/config/http_port.txt
PORT_CONF_FILE_NAME=$BASE_PATH/config/ports.conf
SITE_FILE_NAME=$BASE_PATH/config/cesrc1002-sites
PORT_NUM=`cut -f 2 -d "=" $PORT_TXT_FILE_NAME | tr -d "\r" | tr -d "\n"`

echo "Listen $PORT_NUM" > $PORT_CONF_FILE_NAME
echo "<VirtualHost *:$PORT_NUM>" > $SITE_FILE_NAME
echo "Include ./sites-available/_cesrc1002-sites" >> $SITE_FILE_NAME
echo "</VirtualHost>" >> $SITE_FILE_NAME
