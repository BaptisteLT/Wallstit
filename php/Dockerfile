FROM php:8.1-fpm

# Install required packages
RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    g++ \
    git \
    libicu-dev \
    zip \
    libzip-dev \
    wget \
    && docker-php-ext-install intl opcache pdo pdo_mysql \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

# Setting up the working directory
WORKDIR /var/www/project

# Installing Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Installing Symfony CLI
RUN wget https://get.symfony.com/cli/installer -O - | bash && \
    mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# Set correct permissions for the var directory
#RUN mkdir -p var/cache var/logs && \
#    chmod -R 755 var && \
#    chmod -R 777 var/cache var/logs

# Copy application files
COPY . .



# Copy startup script to set permissions
#COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
#RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Set entrypoint
#ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]


ENV COMPOSER_ALLOW_SUPERUSER=1
# Install Composer dependencies  #TODO: marche pas
RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN composer dump-autoload --optimize --no-dev

# Initialize Symfony application (create cache and logs directories)
RUN php bin/console cache:clear --no-debug --env=prod

#fonctiuonne:  docker-compose -f docker-compose.prod.yaml exec php81-service /bin/bash
#puis chmod -R 777 var 
#TODO: mettre dans un script avec aussi php bin/console cache:clear et composer install --no-dev --optimize-autoloader --no-interaction










####INSTALLATION DES DEPENDANCES JS ET BUILD DU JS#####
# Install Node.js and npm
RUN apt-get update && apt-get install -yqq nodejs npm

# Définit le répertoire de travail dans le conteneur
WORKDIR /var/www/project

# Copy package.json and package-lock.json
COPY package*.json ./

RUN npm install
RUN npm run build