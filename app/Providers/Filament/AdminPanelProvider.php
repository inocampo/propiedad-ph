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
use Filament\Navigation\NavigationItem;
use Filament\Navigation\NavigationGroup;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->spa()
            ->brandName('Conjunto Residencial Gualanday')
            ->brandLogo(asset('images/logo.png'))
            ->brandLogoHeight('3rem')
            ->colors([
                'primary' => Color::Green,
            ])
            
            // Navegación superior
            ->topNavigation()
            ->maxContentWidth('full')
            ->sidebarCollapsibleOnDesktop()
            
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Temporalmente comentamos los widgets hasta verificar que existen
                // \App\Filament\Resources\InvoiceResource\Widgets\WalletStatsWidget::class,
                // \App\Filament\Resources\InvoiceResource\Widgets\OverdueInvoicesWidget::class,
                // \App\Filament\Resources\PaymentResource\Widgets\RecentPaymentsWidget::class,
                // \App\Filament\Resources\PaymentResource\Widgets\MonthlyPaymentsChart::class,
            ])
            ->navigationGroups([
                NavigationGroup::make('Residentes')
                    ->label('Residentes')
                    ->icon('heroicon-o-users')
                    ->collapsed(false),
                    
                NavigationGroup::make('Cartera')
                    ->label('Cartera')
                    ->icon('heroicon-o-banknotes')
                    ->collapsed(false),
                    
                NavigationGroup::make('Comunicaciones')
                    ->label('Comunicaciones')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->collapsed(false),
                    
                NavigationGroup::make('Configuración')
                    ->label('Configuración')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsed(true),
                    
                NavigationGroup::make('Catálogos')
                    ->label('Catálogos')
                    ->icon('heroicon-o-rectangle-stack')
                    ->collapsed(true),
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}