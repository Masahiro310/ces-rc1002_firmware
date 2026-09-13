# /bin/sh

logger "call factorySetting.sh"
# 
/usr/bin/sudo /bin/cp /opt/piLab/config.org/* /opt/piLab/config/
/usr/bin/sudo /bin/chmod 666 /opt/piLab/config/*
/bin/sh /opt/piLab/cmd/updateWebUser.sh

