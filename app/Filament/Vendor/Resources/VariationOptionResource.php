<?php

namespace App\Filament\Vendor\Resources;

use App\Filament\Vendor\Resources\VariationOptionResource\Pages;
use App\Filament\Resources\Vendor\VariationTypeResource\RelationManagers;
use App\Models\Tenant\Clothing\VariationOption as ClothingVariationOption;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class VariationOptionResource extends Resource
{
    protected static ?string $model = ClothingVariationOption::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Clothing';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('variation_type_id')
                    ->label('Variation Type')
                    ->required()
                    ->options(function () {
                        // Only variation types for this vendor
                        return \App\Models\Tenant\Clothing\VariationType::where('vendor_id', Auth::id())->pluck('name', 'id');
                    }),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('image_path')
                    ->label('Image')
                    ->image()
                    ->directory('variation-options')
                    ->preserveFilenames()
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('variationType.name')->label('Variation Type')->sortable(),
                Tables\Columns\ImageColumn::make('image_path')->label('Image'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('variation_type_id')
                    ->label('Variation Type')
                    ->relationship('variationType', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
        return parent::getEloquentQuery()->where('vendor_id', $user->id);
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVariationOptions::route('/'),
            'create' => Pages\CreateVariationOption::route('/create'),
            'edit' => Pages\EditVariationOption::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        return $user && $user->vendor && $user->vendor->department === 'clothing';
    }
}
