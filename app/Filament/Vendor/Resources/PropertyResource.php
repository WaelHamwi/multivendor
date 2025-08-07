<?php

namespace App\Filament\Vendor\Resources;

use App\Filament\Vendor\Resources\PropertyResource\Pages;
use App\Models\Tenant\RealEstate\Property;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Shared\Media;
use App\Filament\Resources\PropertyResource\RelationManagers\PropertyFeatureRelationManager;
use App\Filament\Vendor\Resources\PropertyResource\Pages\AddPropertyFeature;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationLabel = 'Properties';
    protected static ?string $navigationGroup = 'Real Estate';

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
        return parent::getEloquentQuery()->where('vendor_id', $user->id);
    }

    public static function canViewAny(): bool
    {
        $user = \Filament\Facades\Filament::auth()->user();
        if ($user && $user->vendor) {
            if ($user->vendor->status === 'banned') {
                session()->flash('error', 'You are banned and cannot access properties.');
                return false;
            }
        }
        return $user && $user->vendor && $user->vendor->department === 'real_estate';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Main Property Details Panel (left)
            Forms\Components\Section::make('Property Details')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Title')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Textarea::make('description')
                        ->label('Description')
                        ->rows(4)
                        ->required(),
                    Forms\Components\TextInput::make('price')
                        ->label('Price')
                        ->numeric()
                        ->required(),
                    Forms\Components\TextInput::make('location')
                        ->label('Location')
                        ->required(),
                    Forms\Components\Select::make('property_type')
                        ->label('Property Type')
                        ->options([
                            'house' => 'House',
                            'apartment' => 'Apartment',
                            'villa' => 'Villa',
                        ])
                        ->required(),
                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'available' => 'Available',
                            'sold' => 'Sold',
                            'pending' => 'Pending',
                        ])
                        ->required(),
                    Forms\Components\FileUpload::make('images')
                        ->label('Property Images')
                        ->image()
                        ->multiple()
                        ->directory('properties/images'),
                ])
                ->columns(2),  // Split form into two columns

            // Property Features Panel (right sidebar)
            Forms\Components\Section::make('Property Features')
                ->schema([
                    Forms\Components\RelationshipRepeater::make('features')
                        ->label('Property Features')
                        ->relationship('features', function ($query) {
                            return $query->select('feature_name', 'feature_value');
                        })
                        ->schema([
                            Forms\Components\TextInput::make('feature_name')
                                ->label('Feature Name')
                                ->required(),
                            Forms\Components\TextInput::make('feature_value')
                                ->label('Feature Value')
                                ->required(),
                        ])
                        ->columns(2),
                ])
                ->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('price')->money('USD')->sortable(),
            Tables\Columns\TextColumn::make('location')->searchable(),
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
                    $modelType = get_class($record);
                    $media = Media::on('mysql')
                        ->where('model_type', $modelType)
                        ->where('model_id', $record->getKey())
                        ->where('collection_name', 'images')
                        ->latest()
                        ->first();
                    return $media ? url('storage/properties/images/' . $media->file_name) : url('images/placeholder.jpg');
                })
                ->size(60)
                ->circular(),
            Tables\Columns\TextColumn::make('features_count')
                ->label('Features Count')
                ->getStateUsing(function ($record) {
                    return $record->features()->count();
                })
                ->sortable(),
            Tables\Columns\TextColumn::make('features_summary')
                ->label('Property Features')
                ->tooltip(function ($record) {
                    return $record->features->map(fn($feature) => "{$feature->feature_name}: {$feature->feature_value}")
                        ->join("\n");
                })
                ->getStateUsing(function ($record) {
                    return $record->features->map(fn($f) => "{$f->feature_name}")->take(2)->join(', ') . '...';
                }),


        ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('add_feature')
                    ->label('Add Feature')
                    ->url(fn(Property $record): string => route('filament.vendor.resources.properties.add_feature', ['property_id' => $record->id]))
                    ->color('success')
                    ->icon('heroicon-o-plus'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PropertyFeatureRelationManager::class,
        ];
    }

    public static function route(): string
    {
        return '/vendor/properties/{record}/features/create';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperty::route('/create'),
            'edit' => Pages\EditProperty::route('/{record}/edit'),
            'add_feature' => Pages\AddPropertyFeature::route('/features/create'),
        ];
    }
}
