#! /bin/bash

# chooystickのモデル番号
# 最新のモデル番号
CESRC1002_LAST_MODEL_NUMBER=1
# 一番古いモデル番号
CESRC1002_OLDEST_MODEL_NUMBER=1

TMP_DIR=/tmp
WORK_DIR=$TMP_DIR/work
CESRC1002_CONF_DIR=ces_rc1002_1/config
STAT=0

if [ $# -ne 1 ]; then
	exit 1
fi
# アップロードファイル名
ZIP_FILE_NAME=$1

# 作業ディレクトリ作成
mkdir -p $WORK_DIR
# zipを解凍
unzip $TMP_DIR/$ZIP_FILE_NAME -d $WORK_DIR > /dev/null

# ファイル名を強制的に*.zipに変更するので、zip形式でないファイルもzipになるが、
# unzipでエラーが起こるものはzip形式のファイルではないため、エラー
status=`echo $?`
if [ $status -ne 0 ]; then
	STAT=2
fi

conf_key[0]="UnitSetting";
conf_key[1]="CameraSetting";
conf_key[2]="AileunSetting";
conf_key[3]="InterlockSetting";
conf_key[4]="CameraCustom";
#conf_key[5]="CameraLog";
#conf_key[6]="AileunLog";

if [ $STAT -eq 0 ]; then
	STAT=3
	# 最新のモデルから下位モデルの設定ファイルを検索する
	for no in `seq $CESRC1002_LAST_MODEL_NUMBER -1 $CESRC1002_OLDEST_MODEL_NUMBER`
	do
		CESRC1002_CONF_DIR=`printf "ces_rc1002_%d/config" $no`
		IS_EXIST_CONF_DIR=0
		if [ -e $WORK_DIR/$CESRC1002_CONF_DIR ]; then
			IS_EXIST_CONF_DIR=1
		fi
		if [ $IS_EXIST_CONF_DIR = 1 ]; then
			if [ ! -e "$WORK_DIR/$CESRC1002_CONF_DIR/http_port.txt" ]; then
				STAT=4
			fi
			if [ ! -e "$WORK_DIR/$CESRC1002_CONF_DIR/interfaces" ]; then
				STAT=4
			fi

			# 必須の設定ファイルがあるかチェック
			for val in "${conf_key[@]}"
			do
				used_xml=`echo "cat /FileList/$val" | xmllint --shell /opt/piLab/config.org/Config.xml`
				file_name=`echo ${used_xml} | sed -e "s/^.*<$val>\(.*\)<\/$val>.*$/\1/"`
				tmpfile_name=`echo ${file_name} | sed -e "s/opt\/piLab/tmp\/work\/ces_rc1002_$no/g"`
				if [ ! -e $tmpfile_name ]; then
					echo $tmpfile_name
					STAT=4
				fi
			done
			if [ $STAT = 3 ]; then
				STAT=0
			fi
			break
	 	fi
	done
fi

if [ $STAT -ne 0 ]; then
	rm -f $TMP_DIR/$ZIP_FILE_NAME
fi
rm -fr $WORK_DIR

exit $STAT
