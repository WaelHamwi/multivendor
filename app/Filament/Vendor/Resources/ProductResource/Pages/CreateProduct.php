<?php

namespace App\Filament\Vendor\Resources\ProductResource\Pages;


use App\Filament\vendor\Resources\ProductResource as ResourcesProductResource;
use App\Services\Vendor\VendorDatabaseService;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ResourcesProductResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
       dd(DB::connection('vendor__db')->getDatabaseName());

        app(VendorDatabaseService::class)->setVendorDatabase('vendor_clothing_db');
        $data['vendor_id'] = Auth::id();
        return $data;
    }
}
