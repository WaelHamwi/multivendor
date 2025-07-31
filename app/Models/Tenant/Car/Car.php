<?php

namespace App\Models\Tenant\Car;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Services\Vendor\VendorDatabaseService;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Traits\CentralMediaConnection;
class Car extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $connection = 'vendor__db';
    protected $table = 'cars';

    protected $fillable = [
        'vendor_id',
        'make',
        'model',
        'year',
        'price',
        'mileage',
        'color',
        'status',
        'created_by',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        app(VendorDatabaseService::class)->setVendorDatabase('vendor_cars_db');
        $this->setConnection('vendor__db');
    }


    public function features()
    {
        return $this->hasMany(CarFeature::class, 'car_id');
    }
    public function images()
    {
        return $this->hasMany(CarImage::class);
    }

    /**
     * Register the conversions for the images.
     */
    public function registerMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(368)
            ->height(232)
            ->sharpen(10);

        $this->addMediaConversion('preview')
            ->width(800)
            ->height(600)
            ->sharpen(10);
    }
}
