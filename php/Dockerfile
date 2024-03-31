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

COPY . .

RUN mkdir -p /var/www/project/var/cache /var/www/project/var/log && \
    chown -R www-data:www-data /var/www/project/var && \
    chmod -R 775 /var/www/project/var


ENV COMPOSER_ALLOW_SUPERUSER=1
# Install Composer dependencies  #TODO: marche pas
RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN composer dump-autoload --optimize --no-dev

# Initialize Symfony application (create cache and logs directories)
RUN php bin/console cache:clear --no-debug --env=prod


####INSTALLING DEPENDENCIES JS AND BUILD JS ASSETS#####
# Install Node.js and npm
#RUN apt-get update && apt-get install -yqq nodejs npm
ENV NODE_VERSION=20.11.0
RUN apt install -y curl
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash
ENV NVM_DIR=/root/.nvm
RUN . "$NVM_DIR/nvm.sh" && nvm install ${NODE_VERSION}
RUN . "$NVM_DIR/nvm.sh" && nvm use v${NODE_VERSION}
RUN . "$NVM_DIR/nvm.sh" && nvm alias default v${NODE_VERSION}
ENV PATH="/root/.nvm/versions/node/v${NODE_VERSION}/bin/:${PATH}"
RUN node --version
RUN npm --version

# Définit le répertoire de travail dans le conteneur
WORKDIR /var/www/project

# Copy package.json and package-lock.json
COPY package*.json ./

#On installe dans un premier temps tous les packages, même ceux de dev car on a besoin de encore pour le build
RUN npm install
RUN npm run build
#Puis une fois que le build est fait on remet les packages de prod
#RUN npm install --production