#composer dump-autoload
php artisan migrate:fresh
php artisan db:seed
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear