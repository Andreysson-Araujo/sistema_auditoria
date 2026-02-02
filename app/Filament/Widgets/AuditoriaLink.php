<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AuditoriaLink extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pagina de Auditoria', 'Iniciar Nova Auditoria')
                ->description('Clique aqui para abrir o formulário externo')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('success')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:bg-gray-50 transition',
                    // Aqui está o segredo: o link para sua página
                    'onclick' => "window.open('" . route('auditoria.index') . "', '_blank')",
                ]),
        ];
    }
}