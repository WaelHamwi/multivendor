<?php

namespace App\Filament\Vendor\Resources\ProductResource\Components;

use App\Models\Tenant\Clothing\VariationType;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\Auth;

class VariationTypePanel extends TableWidget
{
    protected int|string|array $columnSpan = 'full';
    protected static ?string $heading = 'Variation Types';

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                return VariationType::query()
                    ->where('vendor_id', Auth::id());
            })
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('type')->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->url(fn($record) => route('filament.vendor.resources.variation-types.edit', $record)),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->url(route('filament.vendor.resources.variation-types.create')),
            ]);
    }
}
