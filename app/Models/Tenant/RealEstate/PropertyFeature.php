<?php

namespace App\Models\Tenant\RealEstate;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyFeature extends Model
{
    protected $connection = 'vendor__db';
    protected $table = 'property_features';

    protected $fillable = [
        'property_id',
        'feature_name',
        'feature_value'
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
}
