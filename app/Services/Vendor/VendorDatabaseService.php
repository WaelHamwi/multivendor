<?php

namespace App\Services\Vendor;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Exception;

class VendorDatabaseService
{
    /**
     * Create the vendor database if it doesn't exist,
     * set the connection, and run migrations.
     */
    public function createVendorDatabase(string $databaseName): void
    {
        if (empty($databaseName)) {
            throw new Exception("Database name cannot be empty.");
        }

        // Use main DB connection (default MySQL)
        $mainConnection = DB::connection('mysql');
        $databaseExists = $mainConnection->select("SHOW DATABASES LIKE ?", [$databaseName]);

        // Create database if missing
        if (empty($databaseExists)) {
            $mainConnection->statement("CREATE DATABASE IF NOT EXISTS `{$databaseName}`");
        }

        // Set vendor__db to use the new DB
        $this->setVendorDatabase($databaseName);

        // Run migrations inside vendor database
        Artisan::call('migrate', ['--database' => 'vendor__db']);
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
     * Dynamically set vendor__db to the given database.
     */
    public function setVendorDatabase(string $databaseName): void
    {
        if (empty($databaseName)) {
            throw new Exception("Database name cannot be empty.");
        }

        config(['database.connections.vendor__db.database' => $databaseName]);

        // Reset connection
        DB::purge('vendor__db');
        DB::reconnect('vendor__db');
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
     * Run migrations on an existing vendor DB.
     */
    public function runVendorMigrations(string $databaseName): void
    {
        if (!$this->databaseExists($databaseName)) {
            $this->createVendorDatabase($databaseName);
        } else {
            $this->setVendorDatabase($databaseName);
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
