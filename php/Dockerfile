FROM php:8.1-fpm
RUN apt-get update && apt-get install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip wget \
    && docker-php-ext-install intl opcache pdo pdo_mysql \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

#Setting up the working directory
#WORKDIR /var/www/project

#Installing Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#Installing Symfony CLI
RUN wget https://get.symfony.com/cli/installer -O - | bash && \
mv /root/.symfony5/bin/symfony /usr/local/bin/symfony
