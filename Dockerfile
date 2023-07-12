FROM node:lts as npm-stage

WORKDIR /app

COPY package*.json ./

RUN npm install

COPY . .

RUN npm run dev

FROM php:7.2.14-apache

#install all the system dependencies and enable PHP modules
RUN apt-get update && apt-get install -y \
      libicu-dev \
      libpq-dev \
      libmcrypt-dev \
      libpng-dev \
      git \
      zip \
      unzip \
      gnupg \
    && rm -r /var/lib/apt/lists/* \
    && docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd \
    && docker-php-ext-configure mysqli --with-mysqli=mysqlnd \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install gd \
    && docker-php-ext-install zip

#install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

#set our application folder as an environment variable
ENV APP_HOME /var/www/html

#change uid and gid of apache to docker user uid/gid
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

#change the web_root to laravel /var/www/html/public folder
RUN sed -i -e "s/html/html\/public/g" /etc/apache2/sites-enabled/000-default.conf

# enable apache module rewrite
RUN a2enmod rewrite

# forcing HTTPS
ENV HTTPS=true

#copy source files and run composer
COPY --chown=1000:1000 --from=npm-stage /app $APP_HOME

WORKDIR $APP_HOME

RUN composer install --no-interaction

# RUN cp .env-develop .env
# RUN cp .env-prod .env
RUN php artisan cache:clear
RUN php artisan config:cache
#RUN php artisan key:generate
RUN php artisan migrate
# RUN php artisan db:seed
