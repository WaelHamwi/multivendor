<?php

namespace App\Filament\Vendor\Resources;

use App\Filament\Vendor\Resources\PropertyResource\Pages;
use App\Models\Tenant\RealEstate\Property;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationLabel = 'Properties';
    protected static ?string $navigationGroup = 'Real Estate';
    public static function getEloquentQuery(): Builder
    {
       // $user = auth()->user()->vendor;
        $user = auth()->user();
        $query = parent::getEloquentQuery()->where('vendor_id', $user->id);
        return $query;
    }
    public static function canViewAny(): bool
    {
        $user = \Filament\Facades\Filament::auth()->user();
        return $user && $user->vendor && $user->vendor->department === 'real_estate';
    }


    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')
                ->label('Title')
                ->required()
                ->maxLength(255),
            Forms\Components\Textarea::make('description')
                ->label('Description')
                ->rows(4)
                ->required(),
            Forms\Components\TextInput::make('price')
                ->label('Price')
                ->numeric()
                ->required(),
            Forms\Components\TextInput::make('location')
                ->label('Location')
                ->required(),
            Forms\Components\Select::make('property_type')
                ->label('Property Type')
                ->options([
                    'house' => 'House',
                    'apartment' => 'Apartment',
                    'villa' => 'Villa',
                ])
                ->required(),
            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'available' => 'Available',
                    'sold' => 'Sold',
                    'pending' => 'Pending',
                ])
                ->required(),
            Forms\Components\FileUpload::make('images')
                ->label('Property Images')
                ->image()
                ->multiple()
                ->directory('properties/images'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('price')->money('USD')->sortable(),
            Tables\Columns\TextColumn::make('location')->searchable(),
            Tables\Columns\BadgeColumn::make('status')
                ->colors([
                    'success' => 'available',
                    'danger' => 'sold',
                    'warning' => 'pending',
                ]),
            Tables\Columns\TextColumn::make('created_at')->dateTime(),
        ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // You can create RelationManagers for PropertyFeature or PropertyImage
        ];
    }

    public static function getPages(): array
    {

        return [
            'index' => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'edit' => Pages\EditProperty::route('/{record}/edit'),
        ];
    }
}
