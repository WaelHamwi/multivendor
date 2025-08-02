<?php

namespace App\Filament\Vendor\Resources\CarResource\Pages;

use App\Filament\Vendor\Resources\CarResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant\Car\Car;

class CreateCar extends CreateRecord
{
    protected static string $resource = CarResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        //dd($data);
        $data['vendor_id'] = Auth::id();
        $data['created_by'] = Auth::id();
        return $data;
    }
    protected function afterCreate(): void
    {
        foreach ($this->data['images'] as $file) {
            $this->getRecord() //This method likely retrieves the current database record
                // (model instance) that the method is operating on. 
                //It could return a model, 
                //This method likely retrieves the current database record (model instance)
                // that the method is operating on.
                // It could return a model, like an Eloquent model in Laravellike an Eloquent model in Laravel
                ->addMediaFromDisk($file, 'public')
                ->preservingOriginal()
                ->toMediaCollection('images', 'public');
        }
    }
}
