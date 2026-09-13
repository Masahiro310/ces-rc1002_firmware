#!/bin/sh
#
# cesrc1002 Software updater

cesrc1002_install ()
{
	LOGGER="logger -t cesrc1002_install "
	$LOGGER "cesrc1002-install.sh called"
	/usr/bin/dotnet /opt/installer/StartUpMessage8.dll /opt/installer/cesrc1002/update.txt
	CESRC1002_PKG=/opt/installer/cesrc1002/cesrc1002.tar.gz
	INSTALL_DIR=/opt/
	CHKTMP_DIR=/tmp/cesrc1002
	CHECKSUM_FILE=cesrc1002.txt
	DEFAULT_DIR=`pwd`
	if [ -e $CESRC1002_PKG ] ; then
		mkdir -p $CHKTMP_DIR
		tar zxvfp $CESRC1002_PKG -C $CHKTMP_DIR > /dev/null
		cd $CHKTMP_DIR
		if [ -e $CHECKSUM_FILE ]; then
			md5sum -c $CHECKSUM_FILE > /dev/null
			if [ $? -eq 0 ]; then
				$LOGGER "Software Validation : OK"
				tar zxvfp $CESRC1002_PKG -C $INSTALL_DIR > /dev/null
				$INSTALL_DIR/install.sh $INSTALL_DIR
				sync
				sync
				sync
				rm -f $INSTALL_DIR/install.sh
				rm -f $INSTALL_DIR/cesrc1002.txt
				rm -f $CESRC1002_PKG
			else
				$LOGGER "Software Validation : NG. VersionUP ABORT"
				/bin/plymouth message --text="Failed to Update Application. Fallback booting..."
				/usr/bin/dotnet /opt/installer/StartUpMessage8.dll /opt/installer/cesrc1002/update_ng.txt
				rm -f $CESRC1002_PKG
			fi
		else
			$LOGGER "NOT FOUND MD5SUM.TXT. Software Validation and UpdateProcess will be ABORTED"
			/bin/plymouth message --text="Failed to Update Application. Fallback booting..."
			/usr/bin/dotnet /opt/installer/StartUpMessage8.dll /opt/installer/cesrc1002/update_ng.txt
			rm -f $CESRC1002_PKG
		fi

		cd $DEFAULT_DIR
		rm -rf $CHKTMP_DIR
	fi
	# Finished
	$LOGGER "cesrc1002-install.sh finished"
	sync
}
