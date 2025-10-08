<?php

namespace App\Filament\Vendor\Resources\VariationOptionResource\Pages;

use App\Filament\Vendor\Resources\VariationOptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVariationOptions extends ListRecords
{
    protected static string $resource = VariationOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
