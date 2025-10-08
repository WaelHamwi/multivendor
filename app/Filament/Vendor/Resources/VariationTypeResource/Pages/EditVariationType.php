<?php

namespace App\Filament\Vendor\Resources\VariationTypeResource\Pages;

use App\Filament\Vendor\Resources\VariationTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVariationType extends EditRecord
{
    protected static string $resource = VariationTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
