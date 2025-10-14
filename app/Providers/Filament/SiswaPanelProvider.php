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
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Siswa\Pages\Auth\Login;
use App\Filament\Siswa\Pages\Auth\Register;
use App\Filament\Siswa\Pages\DashboardSiswa;
use App\Filament\Siswa\Pages\Profile;
use App\Filament\Widgets\SiswaDashboardStats;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;

class SiswaPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('siswa')
            ->path('siswa')
            ->brandName('Sacti Win Hunter') 
            ->colors([
                'primary' => Color::Green,
            ])
            ->authGuard('siswa')
            ->login(Login::class)
            ->registration(Register::class)
             ->sidebarCollapsibleOnDesktop()
                     ->viteTheme('resources/css/filament/siswa/theme.css')

            ->discoverResources(in: app_path('Filament/Siswa/Resources'), for: 'App\\Filament\\Siswa\\Resources')
            ->discoverPages(in: app_path('Filament/Siswa/Pages'), for: 'App\\Filament\\Siswa\\Pages')

            ->pages([
                // DashboardSiswa::class,
                Profile::class,
            ])
            ->widgets([
                // Daftarkan widget global di sini jika ada
                // SiswaDashboardStats::class,
            ])

            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Ujian')
                    ->items([
                        NavigationItem::make('Daftar Ujian')
                            ->icon('heroicon-o-clipboard-document-check')
                            ->url('/siswa/ujian/daftar'),
                        NavigationItem::make('History Ujian')
                            ->icon('heroicon-o-clock')
                            ->url('/siswa/ujian/history'),
                    ]),

                NavigationGroup::make()
                    ->label('Kejuaraan')
                    ->items([
                        NavigationItem::make('Daftar Kejuaraan')
                            ->icon('heroicon-o-trophy')
                            ->url('/siswa/kejuaraan/daftar'),
                        NavigationItem::make('History Kejuaraan')
                            ->icon('heroicon-o-archive-box')
                            ->url('/siswa/kejuaraan/history'),
                    ]),
            ])

            // ✅ Biarkan Filament otomatis temukan widget dari folder
            ->discoverWidgets(
                in: app_path('Filament/Siswa/Widgets'),
                for: 'App\\Filament\\Siswa\\Widgets'
            )

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
