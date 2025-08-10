<?php

namespace App\Models\Tenant\Clothing;

use App\Services\Vendor\VendorDatabaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $connection = 'vendor__db';
    protected $table = 'products';
    protected $fillable = [
        'vendor_id',
        'subcategory_id',
        'name',
        'slug',
        'description',
        'base_price',
        'base_quantity',
        'status',
        'meta_title',
        'meta_description'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        app(VendorDatabaseService::class)->setVendorDatabase('vendor_clothing_db');
        $this->setConnection('vendor__db');
    }

    // Add these relationships:
    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }



    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }
}
