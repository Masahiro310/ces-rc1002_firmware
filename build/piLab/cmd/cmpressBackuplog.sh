#! /bin/sh

logger -t webapp "cmpressBackuplog.sh"

TIMESTAMP=`date +"%Y%m%d%H%M%S"`
BASE_PATH=`pwd`
DATA_DIR=$BASE_PATH/$1
TMP_DIR=/tmp
WORK_DIR=$TMP_DIR/$TIMESTAMP/ces_rc1002_$TIMESTAMP
ZIP_FILE_NAME=ces_rc1002_support_data_$TIMESTAMP.zip
COMPRESS_FILE=$TMP_DIR/$ZIP_FILE_NAME

rm -f $DATA_DIR/ces_rc1002_support_data_*.zip

# create working directory
mkdir -p $WORK_DIR/config
mkdir -p $WORK_DIR/log

sudo cp /opt/piLab/config/* $WORK_DIR/config/
sudo cp /var/log/syslog* $WORK_DIR/log/
sudo cp /var/log/Xorg* $WORK_DIR/log/
sudo cp /opt/piLab/version.txt $WORK_DIR/log/

if [ -e /var/log/apache2 ]; then
	sudo cp -r /var/log/apache2 $WORK_DIR/log/
fi

cd $TMP_DIR/$TIMESTAMP
sudo zip -A -r $COMPRESS_FILE ./* > /dev/null
sudo rm -fr $TMP_DIR/$TIMESTAMP
sudo chown www-data:www-data $COMPRESS_FILE
sudo mv $COMPRESS_FILE $DATA_DIR/

echo "$ZIP_FILE_NAME"
