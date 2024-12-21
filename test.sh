#!/usr/bin/env bash

. ./.env

docker compose exec -it wordpress /bin/sh -c "cd /var/www/html/wp-content/plugins/$PLUGIN_NAME/ && phpunit"