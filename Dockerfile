# Base PHP image with Alpine Linux
FROM php:8.2-fpm-alpine

# Install system dependencies, Node.js, NPM, Nginx, Supervisord, and PHP extension dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    nodejs \
    npm \
    curl \
    git \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    zip \
    unzip \
    postgresql-dev \
    icu-dev \
    oniguruma-dev

# Install PHP extensions required by Laravel
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql gd zip bcmath intl mbstring

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy package files for caching NPM & Composer layers
COPY package*.json ./
COPY composer*.json ./

# Copy application source code
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Install NPM dependencies and build assets via Vite
RUN npm ci && npm run build && rm -rf node_modules

# Copy Nginx & Supervisord configurations
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Setup directory permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod +x /var/www/html/docker/run.sh

EXPOSE 80

ENTRYPOINT ["/var/www/html/docker/run.sh"]
