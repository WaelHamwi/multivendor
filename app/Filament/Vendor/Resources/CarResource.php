<?php

namespace App\Filament\Vendor\Resources;

use App\Filament\Vendor\Resources\CarResource\Pages;
use App\Models\Tenant\Car\Car;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Shared\Media;

class CarResource extends Resource
{
    protected static ?string $model = Car::class;
    // protected static ?string $navigationIcon = 'heroicon-o-car';
    protected static ?string $navigationLabel = 'Cars';
    protected static ?string $navigationGroup = 'Cars';

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
        return parent::getEloquentQuery()->where('vendor_id', $user->id);
    }
    public static function canViewAny(): bool
    {
        $user = \Filament\Facades\Filament::auth()->user();
        return $user && $user->vendor && $user->vendor->department === 'cars';
    }


    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('make')
                ->label('Make')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('model')
                ->label('Model')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('year')
                ->label('Year')
                ->required()
                ->minValue(1900)
                ->maxValue(date('Y'))
                ->numeric(),

            Forms\Components\TextInput::make('price')
                ->label('Price')
                ->numeric()
                ->required(),

            Forms\Components\TextInput::make('color')
                ->label('Color')
                ->nullable()
                ->maxLength(100),

            Forms\Components\TextInput::make('mileage')
                ->label('Mileage')
                ->nullable()
                ->numeric(),

            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'available' => 'Available',
                    'sold' => 'Sold',
                    'pending' => 'Pending',
                ])
                ->required(),

            Forms\Components\Textarea::make('description')
                ->label('Description')
                ->rows(4)
                ->nullable(),

            Forms\Components\FileUpload::make('images')
                ->label('Car Images')
                ->image()
                ->multiple()
                ->directory('cars/images'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('make')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('model')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('year')->sortable(),
            Tables\Columns\TextColumn::make('price')->money('USD')->sortable(),
            Tables\Columns\BadgeColumn::make('status')
                ->colors([
                    'success' => 'available',
                    'danger' => 'sold',
                    'warning' => 'pending',
                ]),
            Tables\Columns\TextColumn::make('created_at')->dateTime(),

            Tables\Columns\ImageColumn::make('image')
                ->label('Preview')
                ->getStateUsing(function ($record) {
                    // Normalize class name to match how Spatie stores it
                    $modelType = get_class($record); // e.g., App\Models\Tenant\Car\Car

                    // Use `::class` if you're sure what class is saved in media table
                    // $modelType = \App\Models\Tenant\Car\Car::class;

                    $media = Media::on('mysql') // ← force use of multivendor connection

                        ->where('model_type', $modelType)
                        ->where('model_id', $record->getKey())
                        ->where('collection_name', 'images')
                        ->latest()
                        ->first();
    

                   return $media ? url('storage/cars/images/' . $media->file_name) : url('images/placeholder.jpg'); 
                })
                ->size(60)
                ->circular(),
        ])
            ->filters([
                // Add any filters here
            ])
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
            // You can create RelationManagers for CarFeature or CarImage if needed
        ];
    }

    public static function getPages(): array
    {

        return [
            'index' => Pages\ListCars::route('/'),
            'create' => Pages\CreateCar::route('/create'),
            'edit' => Pages\EditCar::route('/{record}/edit'),
        ];
    }
}
