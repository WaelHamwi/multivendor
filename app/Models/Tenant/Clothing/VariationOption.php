<?php

namespace App\Models\Tenant\Clothing;

use Illuminate\Database\Eloquent\Model;


class VariationOption extends Model
{
    protected $fillable = ['variation_type_id', 'name', 'image_path'];

    // Relationship with VariationType
    public function variationType()
    {
        return $this->belongsTo(VariationType::class);
    }
}
