<?php

namespace App\Models\Tenant\RealEstate;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyImage extends Model
{
    protected $connection = 'vendor__db';
    protected $table = 'property_images';

    protected $fillable = [
        'property_id',
        'file_path',
        'is_featured'
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
}
