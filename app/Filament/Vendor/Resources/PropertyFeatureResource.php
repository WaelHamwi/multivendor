<?php

namespace App\Filament\Vendor\Resources;

use App\Filament\Vendor\Resources\PropertyFeatureResource\Pages\Create;
use App\Models\Tenant\RealEstate\PropertyFeature;
use Filament\Resources\Resource;
use Filament\Forms;
use Filament\Tables;

class PropertyFeatureResource extends Resource
{
    protected static ?string $model = PropertyFeature::class;

    protected static ?string $navigationLabel = 'Property Features';
    protected static ?string $navigationGroup = 'Real Estate';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('feature_name')
                ->label('Feature Name')
                ->required(),

            Forms\Components\TextInput::make('feature_value')
                ->label('Feature Value')
                ->required(),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('feature_name'),
            Tables\Columns\TextColumn::make('feature_value'),
        ]);
    }
    public static function route(): string
    {
        return '/vendor/property-features/{record}';  
    }

    public static function getPages(): array
    {
        return [
            'create' => Create::class,
        ];
    }
}
