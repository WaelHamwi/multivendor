<?php

namespace App\Filament\vendor\Resources\ProductResource\Pages;

use App\Filament\vendor\Resources\ProductResource as ResourcesProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ResourcesProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
