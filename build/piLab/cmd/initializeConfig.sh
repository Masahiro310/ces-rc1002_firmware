# /bin/sh

logger "call initializeConfig.sh $1"

TMP_DIR=/tmp
APPS_DIR=/opt/piLab
DONT_REMOVE_FILE_LIST=$APPS_DIR/dont_remove.list
APPS_CONFIG_DIR=$APPS_DIR/config
APPS_CONFIG_BAK_DIR=$TMP_DIR/config_bak
NET_CONF_FILE_LIST=$APPS_DIR/netconf.list

### 削除対象外のconfig以外を除
if [ ! -e $APPS_CONFIG_BAK_DIR ]; then
	mkdir $APPS_CONFIG_BAK_DIR
fi

# 対象外のリストから一致するconfigを一時的に移動
for confFile in `cat $DONT_REMOVE_FILE_LIST`
do
	if [ -e $APPS_CONFIG_DIR/$confFile ]; then
		sudo mv $APPS_CONFIG_DIR/$confFile $APPS_CONFIG_BAK_DIR/$confFile
	fi
done

if [ $1 = 'without_net' ]; then
    logger "backup net config"
    for confFile in `cat $NET_CONF_FILE_LIST`
    do
        if [ -e $APPS_CONFIG_DIR/$confFile ]; then
            sudo mv $APPS_CONFIG_DIR/$confFile $APPS_CONFIG_BAK_DIR/$confFile
        fi
    done
fi

#全初期化
/usr/bin/sudo /bin/cp /opt/piLab/config.org/* /opt/piLab/config/

#ファイルのリストア
/usr/bin/sudo /bin/mv  $APPS_CONFIG_BAK_DIR/* $APPS_CONFIG_DIR/

/usr/bin/sudo /bin/chmod 666 /opt/piLab/config/*
/bin/sh /opt/piLab/cmd/updateWebUser.sh