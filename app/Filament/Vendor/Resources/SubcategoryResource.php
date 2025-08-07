<?php

namespace App\Filament\Vendor\Resources;

use App\Filament\Vendor\Resources\SubcategoryResource\Pages;
use App\Filament\Vendor\Resources\SubcategoryResource\RelationManagers;
use App\Models\Tenant\Clothing\Subcategory as ClothingSubcategory;
use App\Models\Tenant\Clothing\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class SubcategoryResource extends Resource
{
    protected static ?string $model = ClothingSubcategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Subcategories';
    protected static ?string $navigationGroup = 'Clothing';

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        return parent::getEloquentQuery()
            ->whereHas('category', function ($query) use ($user) {
                $query->where('vendor_id', $user->id);
            });
    }

    public static function canViewAny(): bool
    {
        $user = \Filament\Facades\Filament::auth()->user();
        return $user && $user->vendor && $user->vendor->department === 'clothing';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('category_id')
                ->label('Category')
                ->relationship('category', 'name')
                ->searchable()
                ->preload()
                ->required()
                ->getOptionLabelFromRecordUsing(fn($record) => $record->name)
                ->options(function () {
                    $vendorId = Auth::user()->id;

                    return Category::where('vendor_id', $vendorId)
                        ->pluck('name', 'id');
                }),

            Forms\Components\TextInput::make('name')
                ->required()
                ->label('Subcategory Name'),

            Forms\Components\TextInput::make('slug')
                ->unique(ClothingSubcategory::class, 'slug')
                ->required()
                ->label('Slug'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('category.name')
                ->label('Category')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('name')->label('Subcategory Name'),
            Tables\Columns\TextColumn::make('slug')->label('Slug'),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubcategories::route('/'),
            'create' => Pages\CreateSubcategory::route('/create'),
            'edit' => Pages\EditSubcategory::route('/{record}/edit'),
        ];
    }
}
