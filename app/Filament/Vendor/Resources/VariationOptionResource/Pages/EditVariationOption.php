<?php

namespace App\Filament\Vendor\Resources\VariationOptionResource\Pages;

use App\Filament\Vendor\Resources\VariationOptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVariationOption extends EditRecord
{
    protected static string $resource = VariationOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
