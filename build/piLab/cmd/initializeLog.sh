# /bin/sh

logger "call initializeLog.sh"
# 
#ログファイルの初期化
/usr/bin/sudo /bin/cp /opt/piLab/config.org/AileunLog.xml /opt/piLab/config/
/usr/bin/sudo /bin/cp /opt/piLab/config.org/CameraLog.xml /opt/piLab/config/
/usr/bin/sudo /bin/cp /opt/piLab/config.org/DeviceLog.xml /opt/piLab/config/

/usr/bin/sudo /bin/chmod 666 /opt/piLab/config/*
