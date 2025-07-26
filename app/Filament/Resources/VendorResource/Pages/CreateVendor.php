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
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Sanitize the database name properly:
        $databaseNameRaw = strtolower($data['database_name']);
        $databaseNameSlug = preg_replace('/[^a-z0-9]+/', '_', $databaseNameRaw);
        $databaseNameSlug = trim($databaseNameSlug, '_');
        $databaseName = 'vendor_' . $databaseNameSlug;

        $vendor = Vendor::create([
            'user_id'       => $user->id,
            'database_name' => $databaseName,
            'status'        => $data['status'],
            'subscription'  => $data['subscription'],
            'department'    => $data['department'],
        ]);


        $this->record = $vendor;

        $this->afterSave();

        return $vendor;
    }


    protected function afterSave(): void
    {
        $vendor = $this->record; // now this will be set properly
        $databaseService = app(VendorDatabaseService::class);

        try {
            $databaseService->createVendorDatabase($vendor->database_name, $vendor->department);

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
