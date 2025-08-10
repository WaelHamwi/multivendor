<?php

namespace App\Models\Tenant\Clothing;

use App\Services\Vendor\VendorDatabaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VariationOption extends Model
{
    protected $connection = 'vendor__db';
    protected $table = 'variation_options';
    protected $fillable = [
        'variation_type_id',
        'name',
        'image_path'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        app(VendorDatabaseService::class)->setVendorDatabase('vendor_clothing_db');
        $this->setConnection('vendor__db');
    }

    public function variationType(): BelongsTo
    {
        return $this->belongsTo(VariationType::class);
    }
}