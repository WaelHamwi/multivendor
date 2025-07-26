<?php
/*
namespace App\Http\Controllers\Vendor;

use App\Models\Vendor;
use App\Models\User;
use App\Services\Vendor\VendorDatabaseService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class VendorController extends Controller
{
    protected VendorDatabaseService $vendorDatabaseService;

    public function __construct(VendorDatabaseService $vendorDatabaseService)
    {
        $this->vendorDatabaseService = $vendorDatabaseService;
    }

   
    public function create(Request $request)
    {
        dd('das');
        // 1. Validate incoming request
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|string|min:8',
            'database_name'=> 'required|string|max:50|unique:vendors,database_name',
            'status'       => 'required|in:pending,approved,banned',
            'subscription' => 'required|in:free,pro,enterprise',
        ]);

        // 2. Create User
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // 3. Generate clean database name
        $databaseName = 'vendor_' . strtolower(preg_replace('/[^a-z0-9_]/', '_', $validated['database_name']));

        // 4. Create Vendor linked to User
        $vendor = Vendor::create([
            'user_id'       => $user->id,
            'database_name' => $databaseName,
            'status'        => $validated['status'],
            'subscription'  => $validated['subscription'],
        ]);

        // 5. Create the vendor database dynamically
        $this->vendorDatabaseService->createVendorDatabase($databaseName);

        // 6. Redirect
        return redirect()
            ->route('filament.resources.vendors.index')
            ->with('success', 'Vendor and User created successfully with dynamic DB.');
    }
}*/