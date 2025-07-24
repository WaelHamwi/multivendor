<?php

namespace App\Filament\Resources\VendorResource\Pages;

use App\Filament\Resources\VendorResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
class CreateVendor extends CreateRecord
{
    protected static string $resource = VendorResource::class;

    protected function afterSave(): void
    {
        parent::afterSave();

        $vendor = $this->record;
        $this->createVendorDatabase($vendor);
        $this->runVendorMigrations($vendor);
    }

    // Create vendor's database
    public function createVendorDatabase($vendor)
    {
        // Dynamically create the vendor's database
        $databaseName = 'vendor_' . $vendor->id . '_db';
        DB::statement("CREATE DATABASE IF NOT EXISTS `$databaseName`");
        $vendor->database_name = $databaseName;
        $vendor->save();
    }

    // Run vendor migrations
    public function runVendorMigrations($vendor)
    {
        Artisan::call('migrate', ['--database' => 'vendor']);
    }
}
