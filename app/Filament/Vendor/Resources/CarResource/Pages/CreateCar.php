<?php

namespace App\Filament\Vendor\Resources\CarResource\Pages;

use App\Filament\Vendor\Resources\CarResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateCar extends CreateRecord
{
    protected static string $resource = CarResource::class;
        protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['vendor_id'] = Auth::id();  
        $data['created_by'] = Auth::id();  

        return $data;
    }
}
