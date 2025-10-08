<?php

namespace App\Models\Tenant\Clothing;

use App\Services\Vendor\VendorDatabaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VariationType extends Model
{
    protected $connection = 'vendor__db';
    protected $table = 'variation_types';
    protected $fillable = [
        'product_id',
        'name',
        'type',
        'vendor_id'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        app(VendorDatabaseService::class)->setVendorDatabase('vendor_clothing_db');
        $this->setConnection('vendor__db');
    }

    /*public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
*/
    public function options(): HasMany
    {
        return $this->hasMany(VariationOption::class);
    }
}
