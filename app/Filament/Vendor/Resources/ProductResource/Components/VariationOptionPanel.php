<?php

namespace App\Filament\Vendor\Resources\ProductResource\Components;

use App\Models\Tenant\Clothing\VariationOption;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\Auth;

class VariationOptionPanel extends TableWidget
{
    protected int|string|array $columnSpan = 'full';
    protected static ?string $heading = 'Variation Options';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn() => VariationOption::query()->where('vendor_id', Auth::id()))
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('variationType.name')->label('Variation Type')->sortable(),
                Tables\Columns\ImageColumn::make('image_path')->label('Image'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(fn($record) => route('filament.vendor.resources.variation-options.edit', $record)),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->url(route('filament.vendor.resources.variation-options.create')),
            ]);
    }
}
