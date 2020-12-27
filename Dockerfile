FROM php:7.4-apache

RUN apt-get update && \
    apt-get install -y --no-install-recommends \
# Tools
    apt-utils \
    wget \
    git \
    nano \
    iputils-ping \
    locales \
    unzip \
    zip \
    xz-utils \
    vim \
    libaio1 \
    libaio-dev \
    build-essential \
    libzip-dev \
    libxml2-dev \
    libmcrypt-dev \
    libpng-dev \
    sqlite3 \
    libsqlite3-dev

# lumen packages
RUN docker-php-ext-install tokenizer

# ======= composer =======
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#  Configuring Apache
COPY dock-files/apache/apache2.conf /etc/apache2/apache2.conf

RUN  rm /etc/apache2/sites-available/000-default.conf

# Enable rewrite module
RUN a2enmod rewrite

WORKDIR /var/www/html

#create the containers php.ini
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini && \
    sed -i -e "s/^ *memory_limit.*/memory_limit = 4G/g" /usr/local/etc/php/php.ini

COPY ./dock-files/startup.sh /var/startup.sh
