<?php

namespace App\Models\Tenant\Car;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarFeature extends Model
{
    protected $connection = 'vendor__db';
    protected $table = 'car_features';

    protected $fillable = [
        'car_id',
        'feature_name',
        'feature_value'
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_id');
    }
}
