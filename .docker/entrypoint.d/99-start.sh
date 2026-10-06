#!/usr/bin/env bash
set -e

echo "=========================================="
echo "🚀 Duka System — Starting deployment"
echo "=========================================="

cd /var/www/html

# ============================================================
# 1. Composer
# ============================================================
echo "📦 Composer install..."
composer install --no-dev --optimize-autoloader --no-interaction

# ============================================================
# 2. Clear caches
# ============================================================
echo "🧹 Clearing caches..."
php artisan config:clear || true
php artisan route:clear  || true
php artisan view:clear   || true

# ============================================================
# 3. Migrations
# ============================================================
echo "🗄️  Running migrations..."
php artisan migrate --force || true

# ============================================================
# 4. Seeders (idempotent — safe to run on every deploy)
# ============================================================
echo "🌱 Seeding shops (if missing)..."
php artisan db:seed --class=ShopSeeder --force || true

echo "🌱 Seeding stores and categories..."
php artisan db:seed --class=StoreAndCategorySeeder --force || true

# ============================================================
# 5. Cache views / config / routes
# ============================================================
echo "🎨 Caching views..."
php artisan view:cache || true

echo "⚙️  Caching config and routes..."
php artisan config:cache || true
php artisan route:cache  || true

# ============================================================
# 6. Storage + permissions
# ============================================================
echo "🔗 Storage link..."
php artisan storage:link || true

echo "🔒 Permissions..."
chmod -R 775 storage bootstrap/cache || true
chown -R www-data:www-data storage bootstrap/cache || true

# ============================================================
# 7. Sanity check — user & environment
# ============================================================
echo "=========================================="
echo "🔍 DEBUG: Checking admin user..."
echo "=========================================="
php artisan tinker --execute="
\$user = \App\Models\User::where('username', 'mkcoder')->first();
if (\$user) {
    echo '=== ADMIN USER FOUND ===' . PHP_EOL;
    echo 'Username: ' . \$user->username . PHP_EOL;
    echo 'Email: ' . \$user->email . PHP_EOL;
    echo 'Role: ' . \$user->role . PHP_EOL;
    echo 'Active: ' . (\$user->is_active ? 'YES' : 'NO') . PHP_EOL;
    echo 'Hash check: ' . (\Hash::check('mkcoder1234', \$user->password) ? 'TRUE' : 'FALSE') . PHP_EOL;
    echo 'Password length: ' . strlen(\$user->password) . PHP_EOL;
} else {
    echo '=== USER NOT FOUND ===' . PHP_EOL;
}
echo 'SESSION_DRIVER: ' . config('session.driver') . PHP_EOL;
echo 'SESSION_SECURE_COOKIE: ' . (config('session.secure') ? 'TRUE' : 'FALSE') . PHP_EOL;
echo 'APP_URL: ' . config('app.url') . PHP_EOL;
echo 'APP_KEY set: ' . (config('app.key') ? 'YES' : 'NO') . PHP_EOL;
echo 'APP_ENV: ' . config('app.env') . PHP_EOL;
"
echo "=========================================="

# ============================================================
# 8. Verify stores + categories
# ============================================================
echo "📊 Verifying stores and categories..."
php artisan tinker --execute="
\$stores = \App\Models\Store::withCount('categories')->get(['id','name']);
foreach (\$stores as \$s) {
    echo '🏬 ' . \$s->name . ' → ' . \$s->categories_count . ' categories' . PHP_EOL;
}
echo 'Total stores: ' . \$stores->count() . PHP_EOL;
echo 'Total categories: ' . \App\Models\Category::count() . PHP_EOL;
"
echo "=========================================="

echo "=========================================="
echo "✅ Deployment complete!"
echo "=========================================="

return 0