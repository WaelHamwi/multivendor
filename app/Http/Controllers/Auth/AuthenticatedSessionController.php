<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;


class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login page.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('auth/login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // Role-based redirect using Spatie
        if ($user->getRoleNames()[0] === "admin") {
            return redirect()->intended(route('admin.dashboard'));
        }

        if ($user->getRoleNames()[0] === "vendor") {
            switch ($user->vendor_type) {
                case 'real_estate':
                    return redirect()->intended(route('filament.vendor.resources.properties.index'));

                case 'cars':
                    return redirect()->intended(route('filament.vendor.resources.cars.index'));

                case 'clothing':
                    return redirect()->intended(route('filament.vendor.resources.clothing.index'));

                default:
                    return redirect()->intended(route('filament.vendor.pages.dashboard'));
            }
        }

        // Default for customer or no role
        return redirect()->intended(route('home'));
    }



    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
