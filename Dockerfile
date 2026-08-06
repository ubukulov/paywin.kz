FROM oven/bun:canary AS frontend

WORKDIR /app

COPY package.json bun.lock ./
RUN bun install --frozen-lockfile

COPY tailwind.config.js ./
COPY resources/css ./resources/css
COPY resources/js ./resources/js
COPY resources/views ./resources/views
RUN bun run build:css

FROM php:8.2-fpm-alpine

# Установка Nginx, Supervisor и необходимых системных библиотек
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    libpng-dev \
    libxml2-dev \
    icu-dev \
    linux-headers \
    zip \
    unzip \
    libzip-dev \
    oniguruma-dev

# Установка расширений PHP для Laravel и MySQL
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip intl sockets

# Копируем Composer из официального образа
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Копируем исходный код Laravel
COPY . .

COPY --from=frontend /app/public/css/tailwindcss.css /var/www/html/public/css/tailwindcss.css


# Установка PHP-зависимостей без dev-пакетов
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Выставляем права на папки storage и cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Копируем конфиги Nginx, Supervisor и скрипт запуска
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["entrypoint.sh"]
