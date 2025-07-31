<?php

namespace App\Http\Responses;

use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\LoginResponse as BaseLoginResponse;
use Illuminate\Http\RedirectResponse;

class VendorLoginResponse extends BaseLoginResponse
{
    public function toResponse($request): RedirectResponse
    {
        $user = Filament::auth()->user();

        if (!$user || $user->role !== 'vendor') {
            Filament::auth()->logout();
            return redirect()->route('admin.login')
                ->withErrors(['email' => 'Unauthorized access.']);
        }

        // Redirect vendor based on department
        if ($user->department === 'real_estate') {
            return redirect()->route('vendor.real_estate.dashboard');
        }

        if ($user->department === 'cars') {
            return redirect()->route('vendor.cars.dashboard');
        }

        return redirect()->route('vendor.dashboard');
    }
}
