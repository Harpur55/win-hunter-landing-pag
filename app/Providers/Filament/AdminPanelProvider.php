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
use Filament\Pages\Auth\Register;
use App\Livewire\Auth\CustomLogin;
use Filament\Navigation\NavigationGroup;;
use Filament\Navigation\NavigationItem;




class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('cms.winhunter')
             ->login(CustomLogin::class)
           
            ->registration(\Filament\Pages\Auth\Register::class)
            ->sidebarCollapsibleOnDesktop()

           
            

           


            // Brand
            // ->brandLogo(asset('assets/images/download.JPG'))
            // ->brandLogoHeight('60px')
            ->brandName('Win Hunter Dashboard')

            // ->login()
            ->colors([
                'primary' => Color::Amber,
            ])

            // Resources, Pages, Widgets
                ->viteTheme('resources/css/filament/admin/theme.css')

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                \Filament\Pages\Dashboard::class, // Dashboard bawaan
                \App\Filament\Pages\KuotaKejuaraanPage::class, // <-- WAJIB TAMBAH

            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                \App\Filament\Widgets\WelcomeWidget::class,
                \App\Filament\Widgets\DashboardStats::class,
                  // custom stats
            ])

            // Middleware
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
               
                
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
            
    }
}