<?php

namespace App\Filament\Vendor\Resources;

use App\Filament\Vendor\Resources\ProductResource\Pages;
use App\Filament\Vendor\Resources\ProductResource\Components\VariationTypePanel;
use App\Models\Tenant\Clothing\Product as ClothingProduct;
use App\Models\Tenant\Clothing\Subcategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ProductResource extends Resource
{
    protected static ?string $model = ClothingProduct::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Clothing';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make()
                ->schema([
                    // LEFT SIDE — Main Product Form
                    Grid::make(2)
                        ->schema([
                            Forms\Components\Section::make('Product Details')
                                ->schema([
                                    TextInput::make('name')
                                        ->required()
                                        ->maxLength(255),

                                    TextInput::make('slug')
                                        ->required()
                                        ->maxLength(255)
                                        ->unique(ignoreRecord: true),

                                    Textarea::make('description')
                                        ->columnSpanFull(),

                                    TextInput::make('price')
                                        ->required()
                                        ->numeric()
                                        ->prefix('$'),

                                    TextInput::make('quantity')
                                        ->required()
                                        ->numeric()
                                        ->minValue(0),

                                    Radio::make('subcategory_id')
                                        ->label('Subcategory')
                                        ->required()
                                        ->options(function () {
                                            $user = Auth::user();
                                            return Subcategory::whereHas('category', function ($query) use ($user) {
                                                $query->where('vendor_id', $user->id);
                                            })->pluck('name', 'id');
                                        }),

                                    ToggleButtons::make('status')
                                        ->required()
                                        ->options([
                                            'draft' => 'Draft',
                                            'published' => 'Published',
                                            'archived' => 'Archived',
                                        ])
                                        ->colors([
                                            'draft' => 'warning',
                                            'published' => 'success',
                                            'archived' => 'danger',
                                        ])
                                        ->inline(),
                                ])
                                ->columns(2),

                            Forms\Components\Section::make('SEO')
                                ->schema([
                                    TextInput::make('meta_title')
                                        ->maxLength(255),
                                    Textarea::make('meta_description')
                                        ->maxLength(255),
                                ])
                                ->columnSpanFull(),
                        ])
                        ->columnSpan(2), // Left side

                    // RIGHT SIDE — Variation Types Sidebar
                    Forms\Components\Section::make('Variation Types')
                        ->schema([
                            Forms\Components\View::make('filament.vendor.product-resource.variation-type-panel'),
                            Forms\Components\View::make('filament.vendor.product-resource.variation-option-panel'),
                        ])

                        ->columnSpan(1)
                        ->extraAttributes(['style' => 'min-width: 320px;']),
                    /*
                    Forms\Components\Section::make('Variation options')
                        ->schema([
                            Forms\Components\View::make('filament.vendor.product-resource.variation-option-panel'),
                        ])

                        ->columnSpan(1)
                        ->extraAttributes(['style' => 'min-width: 320px;']),*/

                ])
                ->columns(3) // 2 cols for main form, 1 col for sidebar
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subcategory.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('status')
                    ->label('Published'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('subcategory')
                    ->relationship('subcategory', 'name'),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
        return parent::getEloquentQuery()->where('vendor_id', $user->id);
    }

    public static function canViewAny(): bool
    {
        $user = \Filament\Facades\Filament::auth()->user();
        return $user && $user->vendor && $user->vendor->department === 'clothing';
    }
}
