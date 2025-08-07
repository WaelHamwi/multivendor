<?php

namespace App\Models\Tenant\Clothing;

use App\Models\Vendor;
use App\Services\Vendor\VendorDatabaseService;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $connection = 'vendor__db';
    protected $fillable = ['name', 'slug', 'vendor_id'];
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        app(VendorDatabaseService::class)->setVendorDatabase('vendor_clothing_db');
        $this->setConnection('vendor__db');
    }
    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    // Relationship with Products
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
