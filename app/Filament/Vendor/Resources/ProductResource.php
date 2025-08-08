<?php

namespace App\Filament\Vendor\Resources;

use App\Filament\Vendor\Resources\ProductResource\Pages;
use App\Models\Tenant\Clothing\Product as ClothingProduct;
use App\Models\Tenant\Clothing\Subcategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Repeater;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProductResource extends Resource
{
    protected static ?string $model = ClothingProduct::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Clothing';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('vendor_id', Auth::id());
    }

    public static function form(Form $form): Form
    {
        return $form->schema([


            Select::make('subcategory_id')
                ->label('Subcategory')
                ->options(function () {
                    return Subcategory::whereHas('category', function ($query) {
                        $query->where('vendor_id', Auth::id());
                    })->pluck('name', 'id');
                })
                ->searchable()
                ->required()
                ->rules([
                    'required',
                    Rule::exists('vendor_clothing_db.subcategories', 'id')
                        ->where(function ($query) {
                            $query->whereHas('category', function ($q) {
                                $q->where('vendor_id', Auth::id());
                            });
                        })
                ]),

            TextInput::make('name')
                ->required()
                ->maxLength(255),

            TextInput::make('slug')
                ->unique(ClothingProduct::class, 'slug')
                ->required()
                ->maxLength(255),

            Textarea::make('description')
                ->required()
                ->rows(5),

            TextInput::make('price')
                ->numeric()
                ->required()
                ->prefix('$'),

            TextInput::make('quantity')
                ->numeric()
                ->required(),

            TextInput::make('meta_title')
                ->required()
                ->maxLength(255),

            TextInput::make('meta_description')
                ->required()
                ->maxLength(255),

            Select::make('status')
                ->options([
                    'active' => 'Active',
                    'draft' => 'Draft',
                    'archived' => 'Archived',
                ])
                ->required(),

            Repeater::make('custom_attributes')
                ->schema([
                    TextInput::make('key')->required()->label('Attribute Key'),
                    TextInput::make('value')->required()->label('Attribute Value'),
                ])
                ->label('Custom Attributes')
                ->addable()
                ->reorderable()
                ->deletable()
                ->collapsed()
                ->default([])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('subcategory.name')->label('Subcategory'),
            Tables\Columns\TextColumn::make('price')->money('usd'),
            Tables\Columns\TextColumn::make('quantity'),
            Tables\Columns\TextColumn::make('status')->badge(),
        ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
