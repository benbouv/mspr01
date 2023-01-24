FROM php:8.1-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --disable-tls && \
    mv composer.phar /usr/local/bin/composer

RUN apt-get update && apt-get install -y npm
RUN npm install -g n
RUN n lts

COPY . /var/www/

COPY ./docker/apache.conf /etc/apache2/sites-available/000-default.conf

RUN cd /var/www && \
    composer install
RUN npm install --force
RUN npm run build

RUN chown -R www-data:www-data "/var/www/var/log"
RUN chown -R www-data:www-data "/var/www/var/cache"
RUN chown -R www-data:www-data "/var/www/public/uploads"

WORKDIR /var/www/

ENTRYPOINT [ "bash", "/var/www/docker/docker.sh" ]