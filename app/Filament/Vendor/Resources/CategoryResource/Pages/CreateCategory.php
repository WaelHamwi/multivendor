<?php

namespace App\Filament\vendor\Resources\CategoryResource\Pages;

use App\Filament\vendor\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['vendor_id'] = Auth::id();
        return $data;
    }
}
