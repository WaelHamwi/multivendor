<?php

namespace App\Models\Tenant\Clothing;

use App\Services\Vendor\VendorDatabaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Models\Vendor\VendorMedia;
use Spatie\MediaLibrary\Models\Media;

class VariationOption extends Model implements HasMedia
{
    use InteractsWithMedia;
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
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->singleFile()
            ->model(VendorMedia::class);
    }
}
