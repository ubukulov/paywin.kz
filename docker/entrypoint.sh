#!/bin/sh

# Кэширование конфигурации и роутов Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Выполнение миграций MySQL при деплое
php artisan migrate --force

# Запуск Nginx и PHP-FPM
exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
