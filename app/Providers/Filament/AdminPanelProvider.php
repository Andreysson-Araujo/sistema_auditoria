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
            ->brandName('Fala Servidor') // Personalizando o nome no topo
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Listando seus widgets personalizados para garantir que apareçam
                \App\Filament\Widgets\AuditoriaStats::class,
                \App\Filament\Widgets\DesempenhoChart::class,
                \App\Filament\Widgets\CentralAuditoriaChart::class,
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
            ])
            ->navigationItems([
                NavigationItem::make('Painel de Feedbacks')
                    ->url(fn() => route('auditoria.index'))
                    ->icon('heroicon-o-presentation-chart-line')
                    ->group('Gestão de Qualidade')
                    ->sort(1),

                NavigationItem::make('Auditorias Pendentes')
                    ->url(fn() => route('auditoria.pendentes'))
                    ->icon('heroicon-o-clock')
                    ->group('Gestão de Qualidade')
                    ->sort(2),
                NavigationItem::make('Relatórios Gerenciais')
                    ->url(fn() => route('auditoria.relatorios'))
                    ->icon('heroicon-o-chart-bar-square')
                    ->group('Gestão de Qualidade')
                    ->sort(3),
                NavigationItem::make('Manual do Sistema')
                ->url(asset('downloads/manual_auditoria.pdf'))
                ->icon('heroicon-o-document-arrow-down')
                ->group('Sobre o sistema') 
                ->sort(5)
            ]);
    }
}
