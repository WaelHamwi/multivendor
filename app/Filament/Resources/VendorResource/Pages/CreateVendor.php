<?php

namespace App\Filament\Resources\VendorResource\Pages;

use App\Filament\Resources\VendorResource;
use App\Models\User;
use App\Models\Vendor;
use App\Services\Vendor\VendorDatabaseService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateVendor extends CreateRecord
{
    protected static string $resource = VendorResource::class;

    protected function handleRecordCreation(array $data): Vendor
    {
        // 1. Create user
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // 2. Generate vendor DB name
        $databaseName = 'vendor_' . strtolower(preg_replace('/[^a-z0-9_]/', '_', $data['database_name']));

        // 3. Create vendor and return it
        $vendor = Vendor::create([
            'user_id'       => $user->id,
            'database_name' => $databaseName,
            'status'        => $data['status'],
            'subscription'  => $data['subscription'],
        ]);

        // Set the record manually for afterSave()
        $this->record = $vendor;

        // Now call afterSave yourself, since Filament won't
        $this->afterSave();

        return $vendor;
    }

    protected function afterSave(): void
    {
        $vendor = $this->record; // now this will be set properly
        $databaseService = app(VendorDatabaseService::class);

        try {
            $databaseService->createVendorDatabase($vendor->database_name);

            Notification::make()
                ->title('Vendor created and database setup successfully!')
                ->success()
                ->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Error creating vendor database')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
