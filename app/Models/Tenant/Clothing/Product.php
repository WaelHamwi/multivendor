<?php

namespace App\Models\Tenant\Clothing;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'price', 'quantity', 'meta_title', 'meta_description', 'status', 'custom_attributes'];

    // Relationship to product images
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    // Relationship with Category
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'subcategory_id');
    }

    public function variations()
    {
        return $this->hasMany(ProductVariation::class);
    }
}
