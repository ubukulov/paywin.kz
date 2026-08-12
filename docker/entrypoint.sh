#!/bin/sh

# Кэширование конфигурации и роутов Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Выполнение миграций MySQL при деплое
php artisan migrate --force

# Синхронизация с текущими файлами после рестарта/редеплоя
mkdir -p \
    storage/app/public/products \
    storage/app/public/product \
    public/upload/partners/images \
    public/qrcodes \
    public/files

php artisan storage:link

chown -R www-data:www-data \
    storage \
    public/upload \
    public/qrcodes \
    public/files

chmod -R ug+rwX \
    storage \
    public/upload \
    public/qrcodes \
    public/files


# Запуск Nginx и PHP-FPM
exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
