<?php

namespace App\Filament\Widgets;

use App\Models\Feedback;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CentralAuditoriaChart extends ChartWidget
{
    protected static ?string $heading = 'Auditorias por Central';
    
    // Define que o gráfico ficará ao lado (metade da largura)
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = '1';

    protected function getData(): array
    {
        // Busca a contagem de feedbacks agrupada pelo nome da central
        $dados = Feedback::query()
            ->join('servidors', 'feedbacks.servidor_id', '=', 'servidors.id')
            ->join('centrals', 'servidors.central_id', '=', 'centrals.id')
            ->select('centrals.central_nome', DB::raw('count(*) as total'))
            ->groupBy('centrals.central_nome')
            ->pluck('total', 'central_nome');

        return [
            'datasets' => [
                [
                    'label' => 'Total de Auditorias',
                    'data' => $dados->values()->toArray(),
                    'backgroundColor' => [
                        '#f59e0b', // Amber 500
                        '#3b82f6', // Blue 500
                        '#10b981', // Emerald 500
                        '#6366f1', // Indigo 500
                    ],
                ],
            ],
            'labels' => $dados->keys()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // Define como gráfico de colunas/barras
    }
}