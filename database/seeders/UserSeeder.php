<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Get roles created by RoleSeeder
        $adminRole = Role::where('name', 'admin')->first();
        $vendorRole = Role::where('name', 'vendor')->first();
        $customerRole = Role::where('name', 'customer')->first();

        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
            ]
        );
        $admin->assignRole($adminRole);

        // Create multiple Vendors
        $vendors = [
            ['name' => 'Vendor 1', 'email' => 'vendor1@example.com'],
            ['name' => 'Vendor 2', 'email' => 'vendor3@example.com'],
        ];

        foreach ($vendors as $vendorData) {
            $vendor = User::firstOrCreate(
                ['email' => $vendorData['email']],
                [
                    'name' => $vendorData['name'],
                    'password' => Hash::make('password123'),
                ]
            );
            $vendor->assignRole($vendorRole);
        }

        // Create multiple Customers
        $customers = [
            ['name' => 'Customer 1', 'email' => 'customer1@example.com'],
            ['name' => 'Customer 2', 'email' => 'customer2@example.com'],
            ['name' => 'Customer 3', 'email' => 'customer3@example.com'],
        ];

        foreach ($customers as $customerData) {
            $customer = User::firstOrCreate(
                ['email' => $customerData['email']],
                [
                    'name' => $customerData['name'],
                    'password' => Hash::make('password123'),
                ]
            );
            $customer->assignRole($customerRole);
        }
    }
}
