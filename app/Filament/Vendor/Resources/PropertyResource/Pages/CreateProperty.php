<?php

namespace App\Filament\Vendor\Resources\PropertyResource\Pages;

use App\Filament\Vendor\Resources\PropertyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateProperty extends CreateRecord
{
    protected static string $resource = PropertyResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['vendor_id'] = Auth::id();
        $data['created_by'] = Auth::id();

        return $data;
    }

    protected function afterCreate(): void
    {
        
        foreach ($this->data['images'] as $file) {
            $this->getRecord()
                ->addMediaFromDisk($file, 'public')
                ->preservingOriginal()
                ->toMediaCollection('images', 'public');
        }
    }
    protected function getRedirectUrl(): string
    {
        return route('filament.vendor.resources.properties.index');
    }
}
