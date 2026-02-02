<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DesempenhoChart extends ChartWidget
{
    protected static ?string $heading = 'Distribuição de Auditorias por Órgão';
    
    // Define a largura do gráfico (opcional)
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        // Agrupa os feedbacks por órgão para contar quantos cada um tem
        $data = DB::table('feedbacks')
            ->join('servidors', 'feedbacks.servidor_id', '=', 'servidors.id')
            ->join('orgaos', 'servidors.orgao_id', '=', 'orgaos.id')
            ->select('orgaos.orgao_nome', DB::raw('count(*) as total'))
            ->groupBy('orgaos.orgao_nome')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total de Auditorias',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => [
                        '#fbbf24', // Amber
                        '#3b82f6', // Blue
                        '#10b981', // Green
                        '#f43f5e', // Rose
                        '#8b5cf6', // Purple
                        '#64748b', // Slate
                    ],
                ],
            ],
            'labels' => $data->pluck('orgao_nome')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}