#! /bin/bash
LOGGER="logger -t web-upload "
CESRC1002_PKG=/opt/installer/cesrc1002/cesrc1002.tar.gz
CESRC1002_EVENT_FILE=/opt/installer/INSTALLCESRC1002
CHKTMP_DIR=/tmp/cesrc1002
CHECKSUM_FILE=cesrc1002.txt
DEFAULT_DIR=`pwd`
STAT=1
if [ -e $CESRC1002_PKG ] ; then
	mkdir -p $CHKTMP_DIR
	tar zxvfp $CESRC1002_PKG -C $CHKTMP_DIR > /dev/null
	cd $CHKTMP_DIR
	if [ -e $CHECKSUM_FILE ]; then
		md5sum -c $CHECKSUM_FILE > /dev/null
		if [ $? -eq 0 ]; then
			$LOGGER "Software Validation : OK"
			STAT=0
		else
			$LOGGER "Software Validation : NG"
		fi
	else
		$LOGGER "NOT FOUND MD5SUM.TXT. Software Validation will be ABORTED"
	fi

	cd $DEFAULT_DIR
	rm -rf $CHKTMP_DIR
fi
exit $STAT
