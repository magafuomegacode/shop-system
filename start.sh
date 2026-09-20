#!/usr/bin/env bash
# ===== Start Script for Duka System =====
# Laravel + Blade (bila Vite/Node)

set -e

echo "=========================================="
echo "🚀 Duka System — Starting deployment"
echo "=========================================="

cd /var/www/html

# ============================================
# 1. Install Composer dependencies
# ============================================
echo "📦 Running composer install..."
composer install --no-dev --optimize-autoloader --no-interaction --working-dir=/var/www/html

# ============================================
# 2. Clear old caches
# ============================================
echo "🧹 Clearing old caches..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan cache:clear || true

# ============================================
# 3. Run migrations (inahitaji database tayari)
# ============================================
echo "🗄️  Running migrations..."
php artisan migrate --force || true

# ============================================
# 4. Cache Blade views (kwa performance)
# ============================================
echo "🎨 Caching Blade views..."
php artisan view:cache || true

# ============================================
# 5. Create storage symlink (kama haipo)
# ============================================
echo "🔗 Creating storage symlink..."
php artisan storage:link || true

# ============================================
# 6. Set proper permissions
# ============================================
echo "🔒 Setting permissions..."
chmod -R 775 storage bootstrap/cache || true
chown -R www-data:www-data storage bootstrap/cache || true

# ============================================
# 7. Deployment complete
# ============================================
echo "=========================================="
echo "✅ Deployment complete!"
echo "=========================================="

# ============================================
# 8. Start the container
# nginx-php-fpm inashughulikia nginx + php-fpm
# ============================================
exec /start.sh