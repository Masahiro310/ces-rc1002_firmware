# /bin/sh
logger -t webapp "restart networking.service"

# サービスとインタフェースを再起動
sleep 1
sudo systemctl restart networking.service
#sudo systemctl restart dhcpcd
#sudo ifconfig down eth0
#sudo ifconfig up eth0
#sudo ifconfig down wlan0
#sudo ifconfig up wlan0
