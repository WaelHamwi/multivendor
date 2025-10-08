<?php
namespace App\Models\Vendor;

use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class VendorMedia extends BaseMedia
{
    // Use the vendor's specific database connection
    protected $connection = 'vendor_clothing_db';
}
