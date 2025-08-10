<?php

namespace App\Models\Tenant\Clothing;

use App\Services\Vendor\VendorDatabaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariation extends Model
{
    protected $connection = 'vendor__db';
    protected $table = 'product_variations';
    protected $fillable = [
        'product_id',
        'price',
        'quantity',
        'image',
        'variation_data'
    ];

    protected $casts = [
        'variation_data' => 'array'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        app(VendorDatabaseService::class)->setVendorDatabase('vendor_clothing_db');
        $this->setConnection('vendor__db');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getOptionsAttribute()
    {
        return VariationOption::findMany($this->variation_data ?? []);
    }
}
