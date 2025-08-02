<?php

namespace App\Models\Tenant\RealEstate;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use app\Models\Vendor;
use App\Services\Vendor\VendorDatabaseService;

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
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        app(VendorDatabaseService::class)->setVendorDatabase('vendor_realestate_db');
        $this->setConnection('vendor__db');
    }
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
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->useDisk('public')
            ->useFallbackUrl('/images/placeholder.jpg')
            ->useFallbackPath(public_path('/images/placeholder.jpg'));
    }
    public function getDirectoryPath(): string
    {
        return 'properties/images';
    }
}
