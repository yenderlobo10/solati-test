FROM php:8.2-fpm

COPY . /usr/src/laravel-app
WORKDIR /usr/src/laravel-app

EXPOSE 8000

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install system dependencies
# Note: package NPM is too long time.
RUN apt-get update
RUN apt-get -y install --fix-missing --no-install-recommends \
    npm \
    git \
    libcurl4 \
    libcurl4-openssl-dev \
    zlib1g-dev \
    libicu-dev \
    unzip \
    libzip-dev \
    zip \
    libbz2-dev \
    locales \
    libmcrypt-dev \
    libpq-dev

# Install php extensions
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip

# Install laravel dependencies
RUN composer install
RUN composer run-script post-autoload-dump \ 
    && composer run-script post-root-package-install \
    && composer run-script post-update-cmd \
    && composer run-script post-create-project-cmd

# Configure Auth:JWT
RUN php artisan jwt:secret --force

# Configure NPM
RUN npm set strict-ssl false
RUN npm install

# NOTE
# RUN artisan serve with option --host=0.0.0.0
# RUN npm run dev before serve app