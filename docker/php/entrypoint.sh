#!/bin/bash
set -e

cd /var/www/html

if [ ! -d "vendor" ]; then
  echo "Installing composer dependencies..."
  composer install --no-interaction --optimize-autoloader
fi

exec "$@"
