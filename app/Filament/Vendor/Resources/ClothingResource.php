<?php

namespace App\Filament\Vendor\Resources;

use App\Filament\Vendor\Resources\CategoryResource;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;

class ClothingResource extends Resource
{
    protected static ?string $navigationLabel = 'Vendor Clothing';
    protected static ?string $navigationGroup = 'Vendor';
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    // Add these required methods
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Your form fields
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Your table columns
            ])
            ->filters([
                // Your filters
            ])
            ->actions([
                // Your actions
            ])
            ->bulkActions([
                // Your bulk actions
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Your relations
        ];
    }

    public static function getPages(): array
    {
        return [];
    }

    public static function getResources(): array
    {
        return [
            CategoryResource::class,
            //  OrderResource::class,
            //  ProductResource::class,
        ];
    }
}
