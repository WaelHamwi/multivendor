<?php

namespace App\Filament\Vendor\Resources;


use App\Filament\Vendor\Resources\VariationTypeResource\Pages;
use App\Filament\Resources\Vendor\VariationTypeResource\RelationManagers;
use App\Models\Tenant\Clothing\VariationType as ClothingVariationType;
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

class VariationTypeResource extends Resource
{
    protected static ?string $model = ClothingVariationType::class;
    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static ?string $navigationGroup = 'Clothing';
    protected static ?string $label = 'Variation Type';
    protected static ?string $pluralLabel = 'Variation Types';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Section::make('Details')
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),

                    Select::make('type')
                        ->options([
                            'radio' => 'Radio',
                            'image' => 'Image',
                            'dropdown' => 'Dropdown',
                        ])
                        ->required(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('type')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
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
            'index' => Pages\ListVariationTypes::route('/'),
            'create' => Pages\CreateVariationType::route('/create'),
            'edit' => Pages\EditVariationType::route('/{record}/edit'),
        ];
    }
}
