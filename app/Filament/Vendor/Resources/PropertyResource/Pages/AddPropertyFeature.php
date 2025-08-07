<?php

namespace App\Filament\Vendor\Resources\PropertyResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Tenant\RealEstate\PropertyFeature;
use Filament\Forms;
use App\Filament\Vendor\Resources\PropertyResource;
use app\Models\Tenant\RealEstate\Property;

class AddPropertyFeature extends CreateRecord
{
    protected static string $resource = PropertyResource::class;

    public $propertyId;

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Hidden::make('property_id')
                ->default($this->propertyId), 

            Forms\Components\TextInput::make('feature_name')
                ->label('Feature Name')
                ->required(),

            Forms\Components\TextInput::make('feature_value')
                ->label('Feature Value')
                ->required(),
        ]);
    }


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure property_id is included
        $data['property_id'] = $this->propertyId;
        return $data;
    }

    protected function handleRecordCreation(array $data): PropertyFeature
    {
        $property = Property::findOrFail($this->propertyId);
        return $property->features()->create($data);
    }

    public function mount(): void
    {
        $this->propertyId = request()->query('property_id');
        parent::mount();
    }

    public function getTitle(): string
    {
        return 'Add Property Feature';
    }
       protected function getRedirectUrl(): string
    {
        return route('filament.vendor.resources.properties.index'); 
    }
}
