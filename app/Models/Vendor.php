<?php

namespace App\Models;

use App\Models\Tenant\Clothing\Category;
use App\Models\Tenant\Clothing\Order;
use App\Models\Tenant\Clothing\Product;
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
        'subscription',
        'department'
    ];




    public function properties()
    {
        return (new \App\Models\Tenant\RealEstate\Property)
            ->setConnection('vendor__db')
            ->where('vendor_id', $this->id);
    }
    public function cars()
    {
        return (new \App\Models\Tenant\Car\Car)
            ->setConnection('vendor__db')
            ->where('vendor_id', $this->id);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
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

    public function categories()
    {
        return (new \App\Models\Tenant\Clothing\Category)->setConnection('vendor_db')->where('vendor_id', $this->id);
    }

    public function products()
    {
        return $this->hasMany(Product::class)->usingConnection($this->getTenantConnectionName());
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }


    public function getTenantConnectionName()
    {
        return 'vendor_' . $this->id . '_db';
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
