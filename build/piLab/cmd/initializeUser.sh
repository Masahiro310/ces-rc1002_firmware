# /bin/sh

logger "call initializeUser.sh"
# 
/bin/cp /opt/piLab/config.org/UnitSetting.xml /opt/piLab/config/
/bin/sh /opt/piLab/cmd/updateWebUser.sh
