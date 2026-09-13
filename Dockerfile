# ベースイメージ
FROM php:8.2-apache

# メタ情報の追加
LABEL version="1.0" 
LABEL description="CES-RC-1002 UI確認環境"  

# 環境変数の設定
ENV PRODUCT=CES-RC-1002
RUN echo ${PRODUCT} SYSTEM0
#RUN apt-get install -y libxml2-utils

# 作業ディレクトリの指定
WORKDIR /opt/piLab

RUN apt-get update
RUN apt-get install -y libxml2-utils
RUN apt-get install -y zip

# アプリのインストール COPY {local path} {image path}
COPY build/piLab/ /opt/piLab/

RUN rm -rf /var/www/html/
RUN ln -s /opt/piLab/www/ /var/www/html
RUN ln -s /opt/piLab/config/ /var/www/config
RUN cp -a /opt/piLab/config.org/* /var/www/config/
RUN chown www-data:www-data /opt/piLab/config/
RUN chown www-data:www-data /opt/piLab/config/*
RUN chown www-data:www-data /opt/piLab/www/data/
RUN /opt/piLab/cmd/updateWebUser.sh
RUN chown www-data:www-data /opt/piLab/www/.htpasswd
RUN echo "99" > /dev/shm/cameras.txt

# 起動時の処理実行
#CMD ["echo","Hello-World"]
