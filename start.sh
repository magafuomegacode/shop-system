#!/usr/bin/env bash
# ===== Start Script for Duka System =====
set -e

echo "=========================================="
echo "🚀 Duka System — Starting deployment"
echo "=========================================="

cd /var/www/html

echo "📦 Composer install..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "🧹 Clearing caches..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

echo "🗄️  Running migrations..."
php artisan migrate --force || true

echo "🎨 Caching views..."
php artisan view:cache || true

echo "🔗 Storage link..."
php artisan storage:link || true

echo "🔒 Permissions..."
chmod -R 775 storage bootstrap/cache || true

echo "=========================================="
echo "✅ Deployment complete!"
echo "=========================================="