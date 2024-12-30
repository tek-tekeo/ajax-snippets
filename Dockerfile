FROM wordpress:latest

#wp-cli, phpunit
RUN apt update && apt install -y wget vim unzip sudo git default-mysql-client subversion && \
  wget https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar && \
  chmod +x wp-cli.phar && \
  mv wp-cli.phar /usr/local/bin/wp && \
  wp --info && \
  wget https://phar.phpunit.de/phpunit-9.phar && \
  chmod +x phpunit-9.phar && \
  mv phpunit-9.phar /usr/local/bin/phpunit && \
  phpunit --version