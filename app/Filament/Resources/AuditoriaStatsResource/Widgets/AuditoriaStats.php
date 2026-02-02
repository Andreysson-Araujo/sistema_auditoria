<?php

namespace App\Filament\Widgets;

use App\Models\Feedback;
use App\Models\Servidor;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AuditoriaStats extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Contador de pendentes (servidores com respostas sem feedback_id)
        $pendentes = Servidor::whereHas('respostas', function ($query) {
            $query->whereNull('feedback_id');
        })->count();

        // Média de nota de todos os feedbacks
        $mediaGeral = Feedback::avg('nota_final') ?? 0;

        return [
            Stat::make('Auditorias Realizadas', Feedback::count())
                ->description('Total de relatórios no sistema')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Formularios Pendentes', $pendentes)
                ->description('Aguardando avaliação')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendentes > 0 ? 'warning' : 'gray'),

            Stat::make('Média de Conformidade', number_format($mediaGeral, 1) . '%')
                ->description('Média global de notas')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('info'),
        ];
    }
}