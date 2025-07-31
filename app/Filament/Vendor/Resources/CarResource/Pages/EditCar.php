<?php

namespace App\Filament\Vendor\Resources\CarResource\Pages;

use App\Filament\Vendor\Resources\CarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditCar extends EditRecord
{
    protected static string $resource = CarResource::class;

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
