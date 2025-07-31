<?php

namespace App\Models\Shared;

use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Media extends BaseMedia
{
    public function getConnectionName()
    {
        return 'mysql'; // ✅ central connection name
    }
}
