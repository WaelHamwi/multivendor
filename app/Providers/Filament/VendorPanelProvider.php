<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Http\Middleware\Authenticate as CustomAuthenticate;
use Filament\Facades\Filament;

class VendorPanelProvider extends PanelProvider
{

    public function panel(Panel $panel): Panel
    {

        $resources = [
            \App\Filament\Vendor\Resources\CarResource::class,
            \App\Filament\Vendor\Resources\PropertyResource::class,
        ];


        return $panel
            ->id('vendor')
            ->path('vendor')
            ->login()
            ->colors([Color::Amber])
            ->resources($resources)
            ->pages([
                Pages\Dashboard::class,
            ])
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                CustomAuthenticate::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
