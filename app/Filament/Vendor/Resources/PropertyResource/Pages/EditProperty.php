<?php

namespace App\Filament\Vendor\Resources\PropertyResource\Pages;

use App\Filament\Vendor\Resources\PropertyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditProperty extends EditRecord
{
    protected static string $resource = PropertyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function afterSave(): void
    {
        
        if (isset($this->data['images'])) {
            foreach ($this->data['images'] as $file) {
                $this->getRecord()
                    ->addMediaFromDisk($file, 'public')
                    ->preservingOriginal()
                    ->toMediaCollection('images', 'public');
            }
        }
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['vendor_id'] = $this->record->vendor_id ?? Auth::id();
        return $data;
    }
}
