<?php

namespace App\Services\Vendor;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Exception;

class VendorDatabaseService
{
    /**
     * Create the department database if it doesn't exist,
     * set the connection, and run department migrations.
     */
    public function createVendorDatabase(string $databaseName, string $department): void
    {
        // Force department-based DB naming
        $departmentDbName = $this->getDepartmentDatabaseName($department);

        $mainConnection = DB::connection('mysql');
        $databaseExists = $mainConnection->select("SHOW DATABASES LIKE '{$departmentDbName}'");

        // Create database if missing
        if (empty($databaseExists)) {
            $mainConnection->statement("CREATE DATABASE IF NOT EXISTS `{$departmentDbName}`");
        }

        // Set vendor__db to use the department DB
        $this->setVendorDatabase($departmentDbName);

        // Run department-specific migrations (only once per department DB)
        $this->runDepartmentSpecificMigrations($department);
    }

    /**
     * Map department to database name.
     */
    private function getDepartmentDatabaseName(string $department): string
    {
        return match ($department) {
            'clothing'    => 'vendor_clothing_db',
            'real_estate' => 'vendor_realestate_db',
            'cars'        => 'vendor_cars_db',
            default       => throw new Exception("Unknown department: {$department}"),
        };
    }

    /**
     * Run department-specific migrations.
     */
    private function runDepartmentSpecificMigrations(string $department): void
    {
        $migrationPath = match ($department) {
            'clothing'    => 'database/migrations/vendor_clothing',
            'real_estate' => 'database/migrations/vendor_realestate',
            'cars'        => 'database/migrations/vendor_cars',
            default       => throw new Exception("Unknown department: {$department}"),
        };

        Artisan::call('migrate', [
            '--database' => 'vendor__db',
            '--path'     => $migrationPath,
            '--force'    => true,
        ]);
    }

    /**
     * Dynamically set the vendor database connection.
     */
    public function setVendorDatabase(string $databaseName): void
    {
        if (empty($databaseName)) {
            throw new Exception("Database name cannot be empty.");
        }

        config(['database.connections.vendor__db.database' => $databaseName]);

        DB::purge('vendor__db');
        DB::reconnect('vendor__db');
    }

    /**
     * Dynamically set the tenant database connection.
     */
    public function setTenantDatabase(string $databaseName): void
    {
        if (empty($databaseName)) {
            throw new Exception("Tenant database name cannot be empty.");
        }

        config(['database.connections.tenant.database' => $databaseName]);

        DB::purge('tenant');
        DB::reconnect('tenant');
    }

    /**
     * Check if a database exists.
     */
    public function databaseExists(string $databaseName): bool
    {
        try {
            $mainConnection = DB::connection('mysql');
            $result = $mainConnection->select("SHOW DATABASES LIKE ?", [$databaseName]);
            return !empty($result);
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Check if a given table exists in vendor__db.
     */
    public function vendorTableExists(string $table = 'vendors'): bool
    {
        try {
            $dbName = config('database.connections.vendor__db.database');
            if (empty($dbName) || !$this->databaseExists($dbName)) {
                return false;
            }
            return Schema::connection('vendor__db')->hasTable($table);
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Safe count of rows in a vendor table (returns 0 if DB/table doesn't exist).
     */
    public function safeCount(string $table): int
    {
        try {
            $dbName = config('database.connections.vendor__db.database');
            if (empty($dbName) || !$this->databaseExists($dbName)) {
                return 0;
            }

            return $this->vendorTableExists($table)
                ? DB::connection('vendor__db')->table($table)->count()
                : 0;
        } catch (\Throwable $e) {
            return 0;
        }
    }

    /**
     * Run migrations on an existing department DB.
     */
    public function runVendorMigrations(string $databaseName, string $department): void
    {
        $departmentDbName = $this->getDepartmentDatabaseName($department);

        if (!$this->databaseExists($departmentDbName)) {
            $this->createVendorDatabase($departmentDbName, $department);
        } else {
            $this->setVendorDatabase($departmentDbName);
            Artisan::call('migrate', ['--database' => 'vendor__db']);
        }
    }

    /**
     * Test current vendor__db connection (debugging).
     */
    public function testConnection(): array|string
    {
        try {
            return DB::connection('vendor__db')->select('SHOW TABLES');
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}
