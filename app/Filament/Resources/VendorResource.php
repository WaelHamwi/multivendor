<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VendorResource\Pages;
use App\Models\Vendor;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;  // Add this to handle custom actions
use App\Services\Vendor\VendorDatabaseService;

class VendorResource extends Resource
{

    protected static ?string $model = Vendor::class;
    // protected static ?string $navigationIcon = 'heroicon-o-office-building';
    protected static ?string $navigationLabel = 'Vendors';
    protected static ?string $navigationGroup = 'Marketplace';

    protected VendorDatabaseService $vendorDatabaseService;

    public function __construct(VendorDatabaseService $vendorDatabaseService)
    {
        $this->vendorDatabaseService = $vendorDatabaseService;
    }

    public static function form(Forms\Form $form): Forms\Form
    {

        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Owner Name')
                    ->required(),

                Forms\Components\TextInput::make('email')
                    ->label('Owner Email')
                    ->email()
                    ->required(),

                Forms\Components\TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required(),

                Forms\Components\TextInput::make('database_name')
                    ->label('Database Name')
                    ->required(),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'banned' => 'Banned',
                    ])
                    ->default('pending'),

                Forms\Components\Select::make('subscription')
                    ->label('Subscription')
                    ->options([
                        'free' => 'Free',
                        'pro' => 'Pro',
                        'enterprise' => 'Enterprise',
                    ])
                    ->default('free'),
            ]);
    }
    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id'),
                TextColumn::make('database_name'),
                TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(function ($state) {
                        return ucfirst($state);
                    }),
                TextColumn::make('subscription')
                    ->label('Subscription')
                    ->formatStateUsing(function ($state) {
                        return ucfirst($state);
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'banned' => 'Banned',
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                // You should register this action here under actions and not in a 'table' method.
                /*  Tables\Actions\Action::make('create_vendor')
                    ->label('Create Vendor Database')
                    ->action(function (Vendor $vendor) {
                        // Execute the custom logic for creating the vendor's database and running migrations
                        app(VendorDatabaseService::class)->createVendorDatabase($vendor->database_name);

                        return redirect()->route('filament.resources.vendors.index')->with('success', 'Vendor created successfully and database setup.');
                    })*/
            ]);
    }




    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVendors::route('/'),
            'create' => Pages\CreateVendor::route('/create'),
            'edit' => Pages\EditVendor::route('/{record}/edit'),
        ];
    }
}
