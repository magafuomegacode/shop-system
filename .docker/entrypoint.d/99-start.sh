#!/usr/bin/env bash
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
php artisan migrate --force --seed || true

echo "🎨 Caching views..."
php artisan view:cache || true

echo "🔗 Storage link..."
php artisan storage:link || true

echo "🔒 Permissions..."
chmod -R 775 storage bootstrap/cache || true
chown -R www-data:www-data storage bootstrap/cache || true

# ===== DEBUG: Check admin user =====
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

# ===== RESET ADMIN PASSWORD (kama haifanyi kazi) =====
# ONDOA hii baada ya kutatua tatizo
php artisan tinker --execute="
\$user = \App\Models\User::where('username', 'mkcoder')->first();
if (\$user) {
    \$user->password = \Hash::make('mkcoder1234');
    \$user->is_active = true;
    \$user->save();
    echo '✅ Admin password reset to: mkcoder1234' . PHP_EOL;
}
"

echo "=========================================="
echo "✅ Deployment complete!"
echo "=========================================="

return 0