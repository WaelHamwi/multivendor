<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Vendor;
use App\Services\Vendor\VendorDatabaseService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;


class VendorController extends Controller
{
    protected $vendorDatabaseService;

    public function __construct(VendorDatabaseService $vendorDatabaseService)
    {
        $this->vendorDatabaseService = $vendorDatabaseService;
    }

    /**
     * Custom logic for vendor dashboard or additional features (if needed).
     */
    public function dashboard()
    {
        // You can add custom logic here for vendor-specific pages
        return view('vendor.dashboard');
    }

    /**
     * Manually create a user and a vendor (inserting the user first).
     */
    public function create(Request $request)
    {
        dd($request);
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'database_name' => 'required|unique:vendors',
            'status' => 'required',
            'subscription' => 'required',
        ]);

        // 1. Create a new user first
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        // 2. Generate the vendor's database name
        $databaseName = 'vendor_' . strtolower(str_replace(' ', '_', $validated['database_name']));

        // 3. Create the Vendor and associate it with the created User
        $vendor = Vendor::create([
            'user_id' => $user->id,
            'database_name' => $databaseName,
            'status' => $validated['status'],
            'subscription' => $validated['subscription'],
        ]);

        // 4. Create the vendor's database and run migrations
        $this->vendorDatabaseService->createVendorDatabase($databaseName);

        // 5. Redirect to the Filament admin panel or another appropriate page
        return redirect()->route('filament.resources.vendors.index')->with('success', 'Vendor and user created successfully.');
    }
}
