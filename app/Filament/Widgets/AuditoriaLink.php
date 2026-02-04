<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AuditoriaLink extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Nova Auditoria', 'Realizar uma Auditoria')
                ->description('Abrir painel de formularios pendentes')
                ->descriptionIcon('heroicon-m-plus-circle')
                ->color('success')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:scale-105 transition-transform',
                    'onclick' => "window.location.href='" . route('auditoria.pendentes') . "'",
                ]),
            // Card para Histórico/Painel
            Stat::make('Painel de Feedbacks', 'Feedbacks Realizados')
                ->description('Pesquisar auditorias realizadas')
                ->descriptionIcon('heroicon-m-magnifying-glass')
                ->color('info')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:scale-105 transition-transform',
                    'onclick' => "window.location.href='" . route('auditoria.index') . "'",
                ]),

            // Card para Relatórios (O que você pediu)
            Stat::make('Relatórios Gerenciais', 'Gerenciar Relatórios')
                ->description('Gerar e vizualizar estatísticas e médias')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('amber')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:scale-105 transition-transform',
                    'onclick' => "window.location.href='" . route('auditoria.relatorios') . "'",
                ]),

            // Card para Nova Auditoria
        ];
    }
}