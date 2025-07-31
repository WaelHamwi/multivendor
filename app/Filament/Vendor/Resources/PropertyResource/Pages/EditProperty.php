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
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['vendor_id'] = $this->record->vendor_id ?? Auth::id();
        return $data;
    }
}
