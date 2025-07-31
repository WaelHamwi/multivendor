<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Authenticate
{
    public function handle($request, Closure $next, ...$guards)
    {

        if (Auth::check()) {
            $user = Auth::user();


            if ($user->vendor) {
                $department = $user->vendor->department;

                // Store the department in the session or another shared place
                view()->share('currentVendor', $department);
                Log::info('Vendor department set in session: ' . $department);
            }
        }

        return $next($request);
    }
}
