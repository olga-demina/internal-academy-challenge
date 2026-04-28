# Stage 1: build (PHP + Node, needed because Wayfinder calls php during vite build)
FROM php:8.3-cli-alpine AS builder

RUN apk add --no-cache nodejs npm sqlite-dev && \
    docker-php-ext-install pdo_sqlite bcmath

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --optimize-autoloader --no-scripts

COPY package.json ./
RUN npm install

COPY . .
RUN cp .env.example .env && php artisan key:generate
RUN npm run build

# Stage 2: runtime (PHP-FPM + Nginx)
FROM php:8.3-fpm-alpine

RUN apk add --no-cache nginx supervisor sqlite-dev && \
    docker-php-ext-install pdo_sqlite bcmath

WORKDIR /app

COPY --from=builder /app/vendor ./vendor
COPY --from=builder /app/public/build ./public/build
COPY . .

COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/entrypoint.sh /entrypoint.sh

RUN chmod +x /entrypoint.sh && \
    chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
