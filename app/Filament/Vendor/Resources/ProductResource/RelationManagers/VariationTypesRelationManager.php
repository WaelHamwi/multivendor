<?php

namespace App\Filament\Vendor\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Tenant\Clothing\VariationType;

class VariationTypesRelationManager extends RelationManager
{
    protected static string $relationship = 'variationTypes'; // Must match the relationship in Product model
    protected static ?string $title = 'Variation Types';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('type')
                    ->label('Type')
                    ->options([
                        'radio' => 'Radio Buttons',
                        'dropdown' => 'Dropdown',
                        'image' => 'Image Upload',
                    ])
                    ->required(),

                Forms\Components\Textarea::make('options')
                    ->label('Options (comma separated for radio/dropdown)')
                    ->maxLength(500)
                    ->helperText('Leave empty for image type'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'radio' => 'success',
                        'dropdown' => 'primary',
                        'image' => 'warning',
                    ]),
                Tables\Columns\TextColumn::make('options')->limit(30),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
