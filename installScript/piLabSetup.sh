# /bin/sh
# 強制ディスクチェック Raspberry Piの場合は `/boot/cmdline.txt` に `fsck.mode=force` を追記すればよいみたい。

sudo -E apt update
#sudo -E apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys 3FA7E0328081BFF6A14DA29AA6A19B38D3D831EF
#echo "deb http://download.mono-project.com/repo/debian raspbianstretch main" | sudo tee /etc/apt/sources.list.d/mono-official.list
# sudo -E apt install dirmngr ca-certificates gnupg
# sudo gpg --homedir /tmp --no-default-keyring --keyring /usr/share/keyrings/mono-official-archive-keyring.gpg --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys 3FA7E0328081BFF6A14DA29AA6A19B38D3D831EF
# echo "deb [signed-by=/usr/share/keyrings/mono-official-archive-keyring.gpg] https://download.mono-project.com/repo/debian stable-raspbianbuster main" | sudo tee /etc/apt/sources.list.d/mono-official-stable.list
sudo -E apt update
sudo -E apt install apache2 -y
sudo -E apt install php php-xml php-gd php-mbstring php-zip libxml2-utils xmlstarlet ntpdate dnsutils zip -y
sudo -E apt install arp-scan iputils-arping -y
# sudo -E apt install jfbterm -y
#Light版ラズパイでの不足パッケージ
sudo -E apt install gpiod i2c-tools -y
sudo -E apt install spi-tools -y

#sudo -E apt install usbmount -y
sudo -E mkdir -p /etc/systemd/system/systemd-udevd.service.d/
if [ ! -e /etc/systemd/system/systemd-udevd.service.d/00-my-custom-mountflags.conf ]; then
	echo "[Service]" >> /etc/systemd/system/systemd-udevd.service.d/00-my-custom-mountflags.conf
	echo "PrivateMounts=no" >> /etc/systemd/system/systemd-udevd.service.d/00-my-custom-mountflags.conf
fi
#sudo -E apt install mono-complete -y

#sudo -E systemctl disable avahi-daemon.service
sudo -E apt remove avahi-daemon -y
sudo -E apt remove dhcpcd5 -y
sudo -E apt autoremove -y
sudo -E apt remove fake-hwclock -y
sudo -E dpkg --purge fake-hwclock
sudo -E systemctl mask bluetooth.service
sudo -E timedatectl set-ntp false
sudo -E systemctl stop mono-xsp4.service

if [ ! -e /etc/apache2/mods-enabled/headers.load ]; then
	sudo -E ln -s ../mods-available/headers.load /etc/apache2/mods-enabled/headers.load
fi
cp piLab_file/hwclock-set /lib/udev/hwclock-set
echo -e "Check configuration."

echo $LANG | grep -i -q "ja"
MESSAGE_LANG=$?

##############################################################
# Enable DS1307 
##############################################################
echo -e "Enable I2C."
raspi-config nonint do_i2c 0
if [ $? -ne 0 ]; then
	if [ ${MESSAGE_LANG} -eq 0 ]; then
		echo -e "\e[31mError: I2CをONにできません。\e[m"
		echo -e "\e[31mインストールを中止します。\e[m"
	else
		echo -e "\e[31mError: Failed to enable I2C.\e[m"
		echo -e "\e[31mInstallation aborted.\e[m"
	fi
	exit 1
fi
echo -e "------> Done."

echo -e "Add i2c-dev to /etc/modules"
cat /etc/modules | grep -i -q "i2c-dev"
if [ $? -ne 0 ]; then
	echo "i2c-dev" >> /etc/modules
fi
echo -e "------> Done."

echo -e "Add rtc-ds1307 to /etc/modules"
cat /etc/modules | grep -i -q "rtc-ds1307"
if [ $? -ne 0 ]; then
	echo "rtc-ds1307" >> /etc/modules
fi
echo -e "------> Done."

# echo -e "Add rtc-pcf8523 to /etc/modules"
# cat /etc/modules | grep -i -q "rtc-pcf8523"
# if [ $? -ne 0 ]; then
# 	echo "rtc-pcf8523" >> /etc/modules
# fi
# echo -e "------> Done."

# echo -e "Add rtc-pcf85063 to /etc/modules"
# cat /etc/modules | grep -i -q "rtc-pcf85063"
# if [ $? -ne 0 ]; then
# 	echo "rtc-pcf85063" >> /etc/modules
# fi
# echo -e "------> Done."

#echo -e "Disable default NTP service"
#systemctl -l | grep -i -q "ntp"
#if [ $? -eq 0 ]; then
#	systemctl stop ntp.service
#	systemctl disable ntp.service
#fi
#systemctl -l | grep -i -q "systemd-timesyncd.service"
#if [ $? -eq 0 ]; then
#	systemctl stop systemd-timesyncd.service
#	systemctl disable systemd-timesyncd.service
#fi
systemctl -l | grep -i -q "fake-hwclock.service"
if [ $? -eq 0 ]; then
	sudo systemctl stop fake-hwclock.service
	sudo systemctl disable fake-hwclock.service
fi
echo -e "------> Done."

echo -e "Edit /etc/default/hwclock"
cat /etc/default/hwclock | grep -i -q "HCTOSYS_DEVICE=rtc0"
if [ $? -eq 0 ]; then
	sed -i 's/#HCTOSYS_DEVICE=rtc0/HCTOSYS_DEVICE=rtc0/g' /etc/default/hwclock
else
	echo "HCTOSYS_DEVICE=rtc0" >> /etc/default/hwclock
fi
echo -e "------> Done."

echo -e "Edit /boot/config.txt"
cat /boot/config.txt  | grep -i -q "dtparam=watchdoc=on"
if [ $? -ne 0 ]; then
	echo "dtparam=watchdoc=on" >> /boot/config.txt
fi
cat /boot/config.txt  | grep -i -q "dtparam=i2c_baudrate=50000"
if [ $? -ne 0 ]; then
	echo "dtparam=i2c_baudrate=50000" >> /boot/config.txt
fi
cat /boot/config.txt  | grep -i -q "dtoverlay=i2c-rtc,ds1307"
if [ $? -ne 0 ]; then
	echo "dtoverlay=i2c-rtc,ds1307" >> /boot/config.txt
fi
cp piLab_file/bcm2835-wdt.conf /etc/modprobe.d/
echo -e "------> Done."

echo -e "Edit /etc/sysctl.conf"
cat /etc/sysctl.conf  | grep -i -q "net.ipv6.conf.all.disable_ipv6 = 1"
if [ $? -ne 0 ]; then
	echo "net.ipv6.conf.all.disable_ipv6 = 1" >> /etc/sysctl.conf
fi
echo -e "------> Done."

echo -e "Edit /etc/systemd/system.conf"
cat /etc/systemd/system.conf  | grep -i -q "#RuntimeWatchdogSec=0"
if [ $? -eq 0 ]; then
	sed -i 's/#RuntimeWatchdogSec=0/RuntimeWatchdogSec=14/g' /etc/systemd/system.conf
fi
echo -e "------> Done."

# echo -e "Edit /etc/usbmount/usbmount.conf"
# cat /etc/usbmount/usbmount.conf | grep -i -q 'MOUNTOPTIONS="sync,noexec,nodev,noatime,nodiratime"'
# if [ $? -eq 0 ]; then
# 	sed -i 's/MOUNTOPTIONS="sync,noexec,nodev,noatime,nodiratime"/MOUNTOPTIONS="sync,nodev,noatime,nodiratime"/g' /etc/usbmount/usbmount.conf
# fi
# echo -e "------> Done."
mkdir -p /media/usb1/

echo -e "Edit /etc/apache2/apache2.conf"
cat /etc/apache2/apache2.conf | grep -i -q 'Mutex file:${APACHE_LOCK_DIR} default'
if [ $? -eq 0 ]; then
	sed -i 's/#Mutex file:${APACHE_LOCK_DIR} default/Mutex file:${APACHE_LOCK_DIR} default/g' /etc/apache2/apache2.conf
else
	echo 'Mutex file:${APACHE_LOCK_DIR} default' >> /etc/apache2/apache2.conf
fi
cat /etc/apache2/apache2.conf | grep -i -q 'Options Indexes FollowSymLinks'
if [ $? -eq 0 ]; then
	sed -i 's/^\tOptions Indexes FollowSymLinks/\tOptions FollowSymLinks/g' /etc/apache2/apache2.conf
fi
echo -e "------> Done."

echo -e "Edit /etc/apache2/conf-available/security.conf"
	cp piLab_file/security.conf /etc/apache2/conf-available/security.conf
echo -e "------> Done."

echo -e "Edit /etc/php/7.4/apache2/php.ini"
cat /etc/php/7.4/apache2/php.ini | grep -i -q 'post_max_size = 8M'
if [ $? -eq 0 ]; then
	sed -i 's/post_max_size = 8M/post_max_size = 80M/g' /etc/php/7.4/apache2/php.ini
else
	echo 'post_max_size = 80M' >> /etc/php/7.4/apache2/php.ini
fi
cat /etc/php/7.4/apache2/php.ini | grep -i -q 'upload_max_filesize = 2M'
if [ $? -eq 0 ]; then
	sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 40M/g' /etc/php/7.4/apache2/php.ini
else
	echo 'upload_max_filesize = 40M' >> /etc/php/7.4/apache2/php.ini
fi
echo -e "------> Done."

#Update cmdline.txt
echo -e "Edit /boot/cmdline.txt"
cat /boot/cmdline.txt | grep -i -q 'fsck.mode=force'
if [ $? -ne 0 ]; then
	echo -n ' fsck.mode=force logo.nologo ipv6.disable=1' >> /boot/cmdline.txt
#cat 入力ファイル名 | tr -d '\r' | tr -d '\n' > 変換後の出力ファイル名
	cat /boot/cmdline.txt | tr -d '\r' | tr -d '\n'> /boot/cmdline2.txt
	mv /boot/cmdline2.txt /boot/cmdline.txt
fi
echo -e "------> Done."

echo -e "Deploy piLab Apps"
if [ ! -d /opt/installer/ ]; then
	tar zxvf piLab_file/installer.tgz -C /opt

	chown www-data:www-data /opt/installer/
	chown pi:pi /opt/installer/*
	chown www-data:www-data /opt/installer/cesrc1002/

	chmod 755 /opt/installer/*.sh
fi

if [ ! -d /opt/piLab/ ]; then
	tar zxvf piLab_file/cesrc1002.tar.gz -C /opt
	rm /opt/cesrc1002.txt /opt/install.sh
	rm /opt/piLab/.DS_Store
	rm /opt/piLab/*/.DS_Store
	rm /opt/piLab/*/*/.DS_Store

	cp /opt/piLab/config.org/* /opt/piLab/config/
	chown www-data:www-data /opt/piLab/www/
	chown www-data:www-data /opt/piLab/www/*
	chown www-data:www-data /opt/piLab/www/*/*

	chown www-data:www-data /opt/piLab/config/
	chown www-data:www-data /opt/piLab/config/*
	chown www-data:www-data /opt/piLab/config.org/
	chown www-data:www-data /opt/piLab/config.org/*
	chown www-data:www-data /opt/piLab/cmd/
	chown www-data:www-data /opt/piLab/cmd/*

	chmod 777 /opt/piLab/*.exe
	chmod 777 /opt/piLab/*.sh
	chmod 777 /opt/piLab/cmd/*.sh
	chmod 777 /opt/piLab/config/
	chmod 777 /opt/piLab/config.org/
	chmod 666 /opt/piLab/config/*
	chmod 666 /opt/piLab/config.org/*
	
	sh /opt/piLab/cmd/updateWebUser.sh
	chown www-data:www-data /opt/piLab/www/.htaccess
	chown www-data:www-data /opt/piLab/www/.htpasswd
	chmod 666 /opt/piLab/www/.htpasswd

fi

if [ ! -d /var/www.org ]; then
	mv /var/www /var/www.org
	ln -s /opt/piLab/www /var/www
fi
if [ ! -e /etc/network/interfaces.org ]; then
	mv /etc/network/interfaces /etc/network/interfaces.org
	ln -s /opt/piLab/config/interfaces /etc/network/interfaces
fi

if [ ! -e /etc/cron.hourly/execNtpdate ]; then
	ln -s /opt/piLab/cmd/execNtpdate.sh /etc/cron.hourly/execNtpdate
fi

if [ ! -e /etc/apache2/ports.conf.org ]; then
	mv /etc/apache2/ports.conf /etc/apache2/ports.conf.org
	ln -s /opt/piLab/config/ports.conf /etc/apache2/ports.conf
fi

if [ ! -e /etc/apache2/sites-enabled/cesrc1002-sites ]; then
	rm /etc/apache2/sites-enabled/*
	cp piLab_file/_cesrc1002-sites /etc/apache2/sites-available/
	ln -s /opt/piLab/config/cesrc1002-sites /etc/apache2/sites-enabled/cesrc1002-sites.conf
fi

if [ ! -e /etc/systemd/system/startup_messaeg.service ]; then
	cp piLab_file/startup_messaeg.service /etc/systemd/system/
	systemctl enable startup_messaeg.service
fi

if [ ! -e /etc/systemd/system/start_app.service ]; then
	cp piLab_file/start_app.service /etc/systemd/system/
	systemctl enable start_app.service
fi

if [ ! -e /etc/sudoers.d/010_www_data ]; then
	cp piLab_file/010_www_data /etc/sudoers.d/
	chmod 440 /etc/sudoers.d/010_www_data
fi

#if [ ! -e /etc/udev/rules.d/99-usb-mount.rules ]; then
#	cp piLab_file/99-usb-mount.rules /etc/udev/rules.d/
#	cp piLab_file/media-usb@.mount /etc/systemd/system/
#	udevadm control --reload-rules
#	udevadm trigger
#fi

#rsyslogは、デフォルトから置き換える
	cp piLab_file/rsyslog /etc/logrotate.d/rsyslog
	chmod 644 /etc/logrotate.d/rsyslog

#Light版ラズパイ用にサービス設定を一部修正
	systemctl enable networking.service
	systemctl disable connman-wait-online.service
	systemctl disable connman.service
	systemctl disable brltty.service

#TZを日本に設定
	timedatectl set-timezone Asia/Tokyo

if [ ${MESSAGE_LANG} -eq 0 ]; then
	echo -e "\e[32mインストールが正常に終了しました。\e[m"
	echo -e "\e[32m設定を反映するには、再起動が必要です。\e[m"
else
	echo -e "\e[32mInstallation succeeded.\e[m"
	echo -e "\e[32mYou need reboot the system in order to have changed activate. \e[m"
fi

