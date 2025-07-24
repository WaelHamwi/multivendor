<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Vendor extends Model
{
    use HasFactory;

    protected $connection = 'mysql'; // Global database connection

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'status',
        'database_name',
        'stripe_account',
        'subscription'
    ];

    // Define the relationship with vendor users
    public function vendorUsers()
    {
        return $this->hasMany(VendorUser::class);
    }

    // Accessor for the vendor's database connection
    public function getConnectionName()
    {
        return 'mysql'; // Default connection for Vendor model itself
    }

    // Define the relationship with the products model (store data in vendor DB)
    public function products()
    {
        // Dynamically set the connection for the vendor's products
        return $this->hasMany(Product::class)->usingConnection($this->getTenantConnectionName());
    }

    // Method to dynamically get the tenant database connection name
    public function getTenantConnectionName()
    {
        return 'vendor_' . $this->id . '_db';  // Dynamically set the connection for the vendor's database
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Use explicit connection in raw queries
    public static function getProductsForVendor($vendorId)
    {
        $vendor = self::findOrFail($vendorId);

        return DB::connection($vendor->getTenantConnectionName())  // Get the vendor-specific DB connection
            ->table('products')  // Query the tenant's products table
            ->where('vendor_id', $vendorId)
            ->get();
    }
}
