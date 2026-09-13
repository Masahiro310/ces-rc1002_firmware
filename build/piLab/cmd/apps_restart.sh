# /bin/sh

logger -t webapp "restart start_app.service"
# サービスとインタフェースを再起動
sudo systemctl restart start_app.service
sudo /usr/bin/dotnet /opt/installer/StartUpMessage8.dll /opt/piLab/message/restart_app_message.txt
sleep 1