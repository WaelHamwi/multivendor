<?php
namespace App\Filament\Vendor\Resources\PropertyFeatureResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Tenant\RealEstate\PropertyFeature;
use Filament\Forms;
use App\Filament\Vendor\Resources\PropertyFeatureResource;

class Create extends CreateRecord
{
    protected static string $resource = PropertyFeatureResource::class;

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('feature_name')
                ->label('Feature Name')
                ->required(),

            Forms\Components\TextInput::make('feature_value')
                ->label('Feature Value')
                ->required(),
        ];
    }

    public function getTitle(): string
    {
        return 'Add Property Feature';  // Page title
    }
}
