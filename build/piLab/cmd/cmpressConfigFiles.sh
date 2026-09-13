#! /bin/sh
logger -t webapp "cmpressConfigFiles.sh"

CES_RC1002_CONFIG_DIR=/opt/piLab/config
ZIP_CONFIG_DIR=ces_rc1002_1
BASE_PATH=`pwd`
DATA_DIR=$BASE_PATH/$1
WORK_DIR=/tmp/$ZIP_CONFIG_DIR/config
COMPRESS_FILE=/tmp/ces_rc1002.zip

rm -f $DATA_DIR/ces_rc1002*

# create working directory
mkdir -p $WORK_DIR

cp $CES_RC1002_CONFIG_DIR/* $WORK_DIR/
rm $WORK_DIR/Config.xml
rm $WORK_DIR/AileunLog.xml
rm $WORK_DIR/CameraLog.xml
rm $WORK_DIR/DeviceLog.xml
rm $WORK_DIR/Calibration.xml
rm $WORK_DIR/ntpStatus.txt
rm $WORK_DIR/ports.conf
rm $WORK_DIR/cesrc1002-sites


cd /tmp
zip -A -r $COMPRESS_FILE $ZIP_CONFIG_DIR/*
rm -fr $WORK_DIR
mv $COMPRESS_FILE $DATA_DIR
