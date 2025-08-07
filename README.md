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


php artisan make:migration create_categories_table --path=database/migrations/vendor_clothing
php artisan make:migration create_subcategories_table --path=database/migrations/vendor_clothing
php artisan make:migration create_products_table --path=database/migrations/vendor_clothing
php artisan make:migration create_product_images_table --path=database/migrations/vendor_clothing
php artisan make:migration create_variation_types_table --path=database/migrations/vendor_clothing
php artisan make:migration create_variation_options_table --path=database/migrations/vendor_clothing
php artisan make:migration create_product_variations_table --path=database/migrations/vendor_clothing
php artisan make:migration create_orders_table --path=database/migrations/vendor_clothing
php artisan make:migration create_order_items_table --path=database/migrations/vendor_clothing


php artisan make:model Tenant/Clothing/Category
php artisan make:model Tenant/Clothing/Subcategory
php artisan make:model Tenant/Clothing/Product
php artisan make:model Tenant/Clothing/ProductImage
php artisan make:model Tenant/Clothing/VariationType
php artisan make:model Tenant/Clothing/VariationOption
php artisan make:model Tenant/Clothing/ProductVariation
php artisan make:model Tenant/Clothing/Order
php artisan make:model Tenant/Clothing/OrderItem

php artisan migrate:rollback --path=database/migrations/vendor_clothing
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS orders, products, categories, subcategories, product_variations, variation_types, variation_options, product_images, migrations;

php artisan make:filament-resource Subcategory --path=app/Filament/Vendor/Resources

php artisan make:filament-resource Subcategory


php artisan make:filament-resource Product


