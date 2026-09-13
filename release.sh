#!/bin/sh

# create "cesrc1002" package
mkdir -p tmp
mkdir -p -m 777 tmp/piLab

# Copy Application
cp -a build/piLab/ tmp/
rm -rf tmp/piLab/www/test_code/

# Application Binary

# Utility script

mkdir -p -m 777 tmp/piLab/config
cp build/install.sh tmp/install.sh
chmod 777 tmp/install.sh

# 追加パッケージ


# 更新aileconf


# 更新ドライバ


#バージョン管理
VERSION_FILE=version.txt
RC1002_VERSION=`dotnet.exe build/piLab/CES_RC1002.dll -v | tr -d '\r'`

echo $RC1002_VERSION

# バージョン情報出力
echo $RC1002_VERSION > $VERSION_FILE
echo `date +%y%m%d%H%M%S` >> $VERSION_FILE
mv $VERSION_FILE tmp/piLab/

cd tmp

find . -type f -print0 | xargs -0 md5sum | awk '{if (match($2, "cesrc1002.txt") == 0) print $0;}' > cesrc1002.txt

cd ..

# create cesrc1002.tar.gz

tar -c -z -v -C tmp -f cesrc1002_$RC1002_VERSION.tar.gz piLab install.sh cesrc1002.txt

rm -rf tmp

echo "done."

