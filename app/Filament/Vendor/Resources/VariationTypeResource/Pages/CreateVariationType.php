<?php

namespace App\Filament\Vendor\Resources\VariationTypeResource\Pages;

use App\Filament\Vendor\Resources\VariationTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateVariationType extends CreateRecord
{
    protected static string $resource = VariationTypeResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['vendor_id'] = Auth::id();
        return $data;
    }
}
