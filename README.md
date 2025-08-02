laravel new multivendor
php artisan key:generate

composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

php artisan migrate


php artisan make:seeder RoleSeeder
php artisan make:seeder UserSeeder
php artisan db:seed



composer require filament/filament 
php artisan filament:install --panels
php artisan make:filament-user
php artisan filament:install


php artisan make:filament-resource Vendor
php artisan make:model Vendor

each vendor could has many users
php artisan make:model VendorUser




composer require stancl/tenancy

php artisan tenancy:install
php artisan migrate

git restore database/migrations/0001_01_01_000000_create_users_table.php
git restore database/migrations/0001_01_01_000001_create_cache_table.php
git restore database/migrations/0001_01_01_000002_create_jobs_table.php

php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" when the package is missing ! 

composer require spatie/laravel-medialibrary
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider"
php artisan migrate

php artisan make:model Tenant

php artisan make:migration tenants --table=tenants

php artisan migrate

php artisan make:filament-panel Vendor



php artisan make:model Tenant/Clothing/Clothing

php artisan make:model Tenant/Car/Car

php artisan make:filament-resource Car

php artisan make:migration create_cars_table --path=database/migrations/vendor_car
php artisan make:migration create_car_features_table --path=database/migrations/vendor_car
php artisan make:migration create_car_images_table --path=database/migrations/vendor_car

php artisan make:middleware EnsureUserIsAdmin

php artisan route:clear
php artisan cache:clear
php artisan view:clear

php artisan route:list 

php artisan make:model Models/Shared/Media

php artisan storage:link

php artisan make:filament-relation-manager PropertyFeatureRelationManager

