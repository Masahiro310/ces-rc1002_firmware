#! /bin/bash
logger -t webapp "uncmpressConfigFile.sh"

# chooystickのモデル番号
# 最新のモデル番号
CESRC1002_LAST_MODEL_NUMBER=1
# 一番古いモデル番号
CESRC1002_OLDEST_MODEL_NUMBER=1


TMP_DIR=/tmp
PILAB_DIR=/opt/piLab
CESRC1002_DIR=$TMP_DIR/ces_rc1002_1
WORK_DIR=$CESRC1002_DIR/config
COMPRESS_FILE=$TMP_DIR/ces_rc1002.zip
CESRC1002_CONFIG_DIR=$PILAB_DIR/config
CESRC1002_CONFIG_ORG_DIR=$PILAB_DIR/config.org
CESRC1002_CONFIG_BAK_DIR=$TMP_DIR/config_bak
CESRC1002_UI_DIR=$PILAB_DIR/ui
DONT_REMOVE_FILE_LIST=$PILAB_DIR/dont_remove.list

### 削除対象外のconfig以外を除
if [ ! -e $CESRC1002_CONFIG_BAK_DIR ]; then
	mkdir $CESRC1002_CONFIG_BAK_DIR
fi

# 対象外のリストから一致するconfigを一時的に移動
for confFile in `cat $DONT_REMOVE_FILE_LIST`
do
	if [ -e $CESRC1002_CONFIG_DIR/$confFile ]; then
		sudo mv $CESRC1002_CONFIG_DIR/$confFile $CESRC1002_CONFIG_BAK_DIR/$confFile
	fi
done
# config以下削除
sudo rm $CESRC1002_CONFIG_DIR/*
# 一時的に移動したconfigを元に戻す
sudo mv $CESRC1002_CONFIG_BAK_DIR/* $CESRC1002_CONFIG_DIR/

if [ -e $CESRC1002_CONFIG_BAK_DIR ]; then
#	sudo rm -fr $CESRC1002_CONFIG_BAK_DIR/
	rm -fr $CESRC1002_CONFIG_BAK_DIR/
fi

### アップロードしたconfigを展開
cd $TMP_DIR
unzip $COMPRESS_FILE > /dev/null

# 最新のモデルから設定ファイルのディレクトリを検索
# 見つからなければ下位モデルのディレクトリを検索していく
for no in `seq $CESRC1002_LAST_MODEL_NUMBER -1 $CESRC1002_OLDEST_MODEL_NUMBER`
do
	CESRC1002_DIR=`printf "%s/ces_rc1002_%d" $TMP_DIR $no`
	WORK_DIR=`printf "%s/config" $CESRC1002_DIR`
	if [ -e $WORK_DIR ]; then
		break
	fi
done

sudo chown www-data:www-data $WORK_DIR/* 

sudo mv $WORK_DIR/* $CESRC1002_CONFIG_DIR/
#不足ファイルがある場合は、初期データをコピー
sudo cp -an $CESRC1002_CONFIG_ORG_DIR/* $CESRC1002_CONFIG_DIR/

sudo chmod 666 $CESRC1002_CONFIG_DIR/*
sudo chown www-data:www-data $CESRC1002_CONFIG_DIR/* 

if [ -e $CESRC1002_CONFIG_DIR/gtkrc.conf ]; then
	sudo cp -f $CESRC1002_CONFIG_DIR/gtkrc.conf $CESRC1002_UI_DIR/gtkrc
fi

sudo rm -fr $COMPRESS_FILE $CESRC1002_DIR
