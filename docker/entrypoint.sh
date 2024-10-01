#!/bin/bash

# Fix permissions for storage and cache directories
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Start PHP-FPM and Nginx
php-fpm & nginx -g 'daemon off;'
