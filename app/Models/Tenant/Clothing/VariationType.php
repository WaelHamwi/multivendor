<?php

namespace App\Models\Tenant\Clothing;

use Illuminate\Database\Eloquent\Model;

class VariationType extends Model
{
    protected $fillable = ['name', 'type'];

    public function options()
    {
        return $this->hasMany(VariationOption::class);
    }
}
