<?php

namespace App\Models\Tenant\Car;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarImage extends Model
{
    protected $connection = 'vendor__db';
    protected $table = 'car_images';

    protected $fillable = [
        'car_id',
        'file_path',
        'is_featured'
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_id');
    }
}
