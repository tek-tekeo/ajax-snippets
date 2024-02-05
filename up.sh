#!/usr/bin/env bash

. ./.env

DIR="/var/www/html/wp-content/plugins/$PLUGIN_NAME"

docker compose down
docker compose up -d --build
sleep 5 # wait for mysql to start
# docker compose exec -it wordpress /bin/bash -c `wp core install --url="http://localhost:$WEB_PORT" --title='新規サイト' --admin_user="$WORDPRESS_ADMIN_USER" --admin_password="$WORDPRESS_ADMIN_PASSWORD" --admin_email="$WORDPRESS_ADMIN_EMAIL" --allow-root`
docker compose exec -it wordpress /bin/bash -c "wp language core install ja --activate --allow-root"

# タイムゾーンと日時表記
docker compose exec -it wordpress /bin/bash -c "wp option update timezone_string 'Asia/Tokyo' --allow-root"
docker compose exec -it wordpress /bin/bash -c "wp option update date_format 'Y-m-d' --allow-root"
docker compose exec -it wordpress /bin/bash -c "wp option update time_format 'H:i' --allow-root"

# テーマのインストール
docker compose exec -it wordpress /bin/bash -c "wp theme install https://wp-cocoon.com/download/791/?tmstv=1707142588 --activate --allow-root"

# プラグインの削除
docker compose exec -it wordpress /bin/bash -c "wp plugin delete hello.php --allow-root"

# プラグインのインストール
docker compose exec -it wordpress /bin/bash -c "wp plugin install tinymce-advanced --activate --allow-root"
docker compose exec -it wordpress /bin/bash -c "wp plugin install classic-editor --activate --allow-root"

# プラグインの初期テンプレートを作成
if [ -e $PLUGIN_NAME".php" ]; then
  docker compose exec -it wordpress /bin/bash -c "yes 's' | wp scaffold plugin $PLUGIN_NAME --allow-root"
fi
# プラグインのアクティブ化
docker compose exec -it wordpress /bin/bash -c "wp plugin activate $PLUGIN_NAME --allow-root"
docker compose exec -it wordpress /bin/bash -c "cd $DIR && yes | bin/install-wp-tests.sh $WORDPRESS_TEST_DB_NAME $WORDPRESS_TEST_DB_USER $WORDPRESS_TEST_DB_PASSWORD $WORDPRESS_TEST_DB_HOST"