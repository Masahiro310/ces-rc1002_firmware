logger -t ces_rc1002_install "call application installer"
logger -t ces_rc1002_install "remount(ro) /boot"
/bin/sudo /bin/mount -o remount,ro /boot

INSTALL_DIR=/opt/piLab

sudo /bin/chmod 777 $INSTALL_DIR/*.exe
sudo /bin/chmod 777 $INSTALL_DIR/config

#新規ファイル等不足ファイルを初期化ファイルからコピー

sudo /bin/chmod 666 $INSTALL_DIR/config/*
sudo /bin/chmod 777 $INSTALL_DIR/www
sudo /bin/chmod 666 $INSTALL_DIR/www/.htpasswd
sudo /bin/chmod 777 $INSTALL_DIR/www/data
sudo /bin/chmod 755 $INSTALL_DIR/cmd/*.sh

if [ ! -e /etc/cron.hourly/getRaspiReport ]; then
	sudo ln -s $INSTALL_DIR/cmd/getRaspiReport.sh /etc/cron.hourly/getRaspiReport
fi
if [ -e $INSTALL_DIR/www/cameraSetting.html ]; then
	sudo /bin/rm $INSTALL_DIR/www/cameraSetting.html
fi

if [ -e $INSTALL_DIR/reboot_message.txt ]; then
	sudo /bin/rm $INSTALL_DIR/reboot_message.txt
fi
if [ -e $INSTALL_DIR/restart_app_message.txt ]; then
	sudo /bin/rm $INSTALL_DIR/restart_app_message.txt
fi

sudo sed -i  -z 's/\/var\/log\/syslog\n{\n\trotate 7/\/var\/log\/syslog\n{\n\trotate 14/g' /etc/logrotate.d/rsyslog

sudo /bin/rm $INSTALL_DIR/.DS_Store
sudo /bin/rm $INSTALL_DIR/*/.DS_Store
sudo /bin/rm $INSTALL_DIR/www/*/.DS_Store
sudo /bin/rm -rf $INSTALL_DIR/www/test_code/
