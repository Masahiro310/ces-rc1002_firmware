# /bin/sh

logger -t webapp "restart apache2.service"
# サービスとインタフェースを再起動
sleep 1
sudo systemctl restart apache2.service
