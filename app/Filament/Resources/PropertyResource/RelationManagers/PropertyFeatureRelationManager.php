<?php

namespace App\Filament\Resources\PropertyResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Forms;
use Filament\Forms\Form;

class PropertyFeatureRelationManager extends RelationManager
{
    protected static string $relationship = 'features';
    public function form(Form $form): Form
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

    public function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('feature_name')->sortable(),
            Tables\Columns\TextColumn::make('feature_value')->sortable(),
        ])->actions([  
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
            ->bulkActions([  
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->headerActions([  
                Tables\Actions\CreateAction::make(),  
            ]);
    }
}
