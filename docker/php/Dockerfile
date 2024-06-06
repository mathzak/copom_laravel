FROM php:8.2-fpm

ARG UID
ARG USER

ENV UID=${UID}
ENV USER=${USER}

RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

RUN useradd -G www-data,root -u $UID -d /home/${USER} ${USER}
RUN mkdir -p /home/${USER}/.composer && chown -R ${USER}:${USER} /home/${USER}

RUN apt update && apt install -y --no-install-recommends apt-utils

RUN apt install -y \
    git \
    curl \
    libonig-dev \
    libpng-dev \
    libpq-dev \
    libxml2-dev \
    libzip-dev \
    unzip \
    zlib1g-dev \
    zip

RUN docker-php-ext-install \
    bcmath \
    calendar \
    exif \
    fileinfo \
    gd \
    iconv \
    mbstring \
    pcntl \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    pgsql \
    session \
    simplexml \
    xml \
    zip

RUN docker-php-ext-configure calendar

RUN apt clean && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www

COPY ./app/.env.example .env
COPY ./app .

RUN chown -R ${USER}:${USER} .

USER ${USER}

RUN composer install
RUN php artisan key:generate
