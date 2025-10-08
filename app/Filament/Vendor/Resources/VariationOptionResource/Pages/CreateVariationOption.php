<?php

namespace App\Filament\Vendor\Resources\VariationOptionResource\Pages;

use App\Filament\Vendor\Resources\VariationOptionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateVariationOption extends CreateRecord
{
    protected static string $resource = VariationOptionResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['vendor_id'] = Auth::id();
        return $data;
    }
}
