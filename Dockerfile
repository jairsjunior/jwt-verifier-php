FROM php:7.4.9-fpm as build

RUN apt-get update \
    && apt-get install -y git unzip libzip-dev

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ADD . /project

WORKDIR /project

RUN composer install

RUN DRY_RUN=true vendor/bin/phpunit

FROM php:7.4.9-fpm

WORKDIR /var/www/html/app

RUN apt-get update \
    && apt-get install -y git unzip libzip-dev netcat
RUN docker-php-ext-configure zip \
    && docker-php-ext-install zip
RUN docker-php-ext-install mysqli pdo pdo_mysql \
    && docker-php-ext-enable pdo_mysql

COPY --from=build /project /var/www/html/app

RUN chown -R www-data:www-data /var/www/html/app

CMD [ "php-fpm" ]