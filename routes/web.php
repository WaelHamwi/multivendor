<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\HomeController;
use Filament\Facades\Filament;
use App\Http\Controllers\Vendor\VendorController;

// Home route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authenticated user routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard route (Inertia-based)
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    // Admin route - use Filament's admin panel


    // Vendor route - customize this based on your vendor dashboard setup
    Route::middleware(['role:vendor'])->group(function () {
        Route::get('/vendor', function () {
            return Inertia::render('vendor/VendorDashboard');
        })->name('vendor.dashboard');
    });

    // Customer route - customize this as needed
    Route::middleware(['role:customer'])->get('/customer', function () {
        return Inertia::render('CustomerDashboard');
    })->name('customer.dashboard');
});

// Additional settings or auth files
require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
