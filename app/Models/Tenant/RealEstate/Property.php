<?php

namespace App\Models\RealEstate;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Property extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $connection = 'vendor__db';
    protected $table = 'properties';

    protected $fillable = [
        'vendor_id',
        'title',
        'description',
        'price',
        'location',
        'property_type',
        'status',
        'created_by',
    ];

    public function features()
    {
        return $this->hasMany(PropertyFeature::class, 'property_id');
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
