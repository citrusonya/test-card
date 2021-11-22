FROM php:7.3-fpm

RUN apt-get update && apt-get install -y \
		ssh \
        curl \
		mcrypt \
        wget \
        git \
        libpng-dev \
		libzip-dev \
        libicu-dev \
		libpq-dev \
		libxml2-dev \
		libmcrypt-dev \
	&& pecl install mcrypt-1.0.3 \
	&& docker-php-ext-enable mcrypt \
    && docker-php-ext-install -j$(nproc) intl pgsql pdo_pgsql mbstring xml zip soap bcmath gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ADD php.ini /usr/local/etc/php/conf.d/40-custom.ini

WORKDIR /var/www/app

CMD ["php-fpm"]