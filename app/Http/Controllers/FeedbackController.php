<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Resposta;
use App\Models\Servidor;
use App\Models\Pergunta;
use App\Models\Nivel;
use App\Models\Central;
use App\Models\Orgao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
    // --- FLUXO DO SERVIDOR ---

    public function identificacao()
    {
        $nivels = Nivel::all();
        $centrals = Central::all();
        $orgaos = Orgao::all();
        return view('auditoria.identificacao', compact('nivels', 'centrals', 'orgaos'));
    }

    public function iniciar(Request $request)
    {
        $servidor = Servidor::create($request->all());
        return redirect()->route('auditoria.perguntas', $servidor->id);
    }

    public function perguntas($servidor_id)
    {
        $perguntas = Pergunta::all();
        return view('auditoria.perguntas', compact('perguntas', 'servidor_id'));
    }

    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {
            $feedback = Feedback::create([
                'servidor_id' => $request->servidor_id,
                'data_auditoria' => now(),
            ]);

            foreach ($request->respostas as $perguntaId => $valor) {
                Resposta::create([
                    'feedback_id' => $feedback->id,
                    'pergunta_id' => $perguntaId,
                    'valor' => $valor,
                ]);
            }
        });
        return "Obrigado por responder!";
    }

    // --- FLUXO DO AUDITOR ---

    public function showPendente($id)
    {
        $servidor = Servidor::with([
            'respostas.pergunta', // <--- Isso carrega a pergunta para cada resposta
            'nivel',
            'orgao',
            'central'
        ])->findOrFail($id);

        return view('auditoria.show_respostas', compact('servidor'));
    }
    public function index(Request $request)
    {
        $query = Feedback::with(['servidor.orgao', 'user']);

        if ($request->filled('orgao_id')) {
            $query->whereHas('servidor', fn($q) => $q->where('orgao_id', $request->orgao_id));
        }

        if ($request->filled('data')) {
            $query->whereDate('created_at', $request->data);
        }

        if ($request->filled('nota_minima')) {
            $query->where('nota_final', '>=', $request->nota_minima);
        }

        // Usamos o appends para anexar os filtros sem usar o 'withQueryString' que buga seu editor
        $feedbacks = $query->latest()->paginate(10);
        $feedbacks->appends($request->all());

        $orgaos = \App\Models\Orgao::orderBy('orgao_nome')->get();

        return view('auditoria.index', compact('feedbacks', 'orgaos'));
    }

    public function show($id)
    {
        $feedback = Feedback::with([
            'servidor.nivel',
            'servidor.orgao',
            'servidor.central',
            'respostas.pergunta',
            'user'
        ])->findOrFail($id);

        return view('auditoria.show', compact('feedback'));
    }

    public function auditoriasPendentes()
    {
        // BUSCA: Servidores que possuem respostas onde o feedback_id é nulo
        $servidores = Servidor::whereHas('respostas', function ($query) {
            $query->whereNull('feedback_id');
        })
            ->with(['orgao', 'nivel', 'central']) // Carrega as infos para a tabela ficar bonita
            ->withCount(['respostas' => function ($query) {
                $query->whereNull('feedback_id');
            }])
            ->latest()
            ->paginate(10);

        return view('auditoria.pendentes', compact('servidores'));
    }

    public function finalizarAuditoria(Request $request, $servidor_id)
    {
        DB::transaction(function () use ($servidor_id, $request) {
            // 1. Pegamos as respostas que ainda não foram auditadas
            $respostas = Resposta::where('servidor_id', $servidor_id)
                ->whereNull('feedback_id')
                ->get();

            // 2. Filtramos apenas as respostas que possuem valor numérico (1 a 5)
            // Isso ignora a pergunta de comentário no cálculo da média base
            $respostasComNota = $respostas->filter(function ($r) {
                return is_numeric($r->valor) && (int) $r->valor > 0;
            });

            $totalPerguntas = $respostasComNota->count();
            $somaNotas = $respostasComNota->sum(fn($r) => (int) $r->valor);
            $pontuacaoMaxima = $totalPerguntas * 5;

            // 3. Cálculo da Nota Base (0 a 100)
            $notaBase = $pontuacaoMaxima > 0 ? ($somaNotas / $pontuacaoMaxima) * 100 : 0;

            // 4. Aplicação do AJUSTE (Vindo dos seus novos botões)
            $valorAjuste = (int) $request->input('ajuste_auditor', 0); // Padrão 0 se não enviado
            $notaFinal = $notaBase + $valorAjuste;

            // Limita a nota entre 0 e 100
            $notaFinal = max(0, min(100, $notaFinal));

            // 5. Criação do Feedback com o ajuste
            $feedback = Feedback::create([
                'servidor_id'    => $servidor_id,
                'user_id'        => auth()->id(),
                'data_auditoria' => now(),
                'nota_final'     => $notaFinal,
                'ajuste_auditor' => $valorAjuste,
                'comentario'     => $request->comentario,
            ]);

            // 6. Vincula as respostas ao feedback criado
            foreach ($respostas as $resp) {
                $resp->update(['feedback_id' => $feedback->id]);
            }

            return $feedback;
        });

        return redirect()->route('auditoria.index')->with('sucesso', 'Auditoria finalizada com sucesso!');
    }

public function relatorios(Request $request)
{
    $centrals = \App\Models\Central::all();
    $orgaos = \App\Models\Orgao::all();

    // 1. Definimos as datas padrão para exibição inicial no Blade
    $dataInicio = $request->input('data_inicio', date('01/m/Y')); 
    $dataFim = $request->input('data_fim', date('d/m/Y'));

    $query = \App\Models\Feedback::with(['servidor.orgao', 'servidor.central']);

    // 2. Filtro de Período com Tratamento de Erros (Formato BR para DB)
    if ($request->filled('data_inicio')) {
        try {
            // trim() remove espaços fantasmas da máscara JS
            $inicio = \Carbon\Carbon::createFromFormat('d/m/Y', trim($request->data_inicio))->startOfDay();
            $query->where('created_at', '>=', $inicio);
        } catch (\Exception $e) {
            // Se a data for inválida, ignoramos o filtro para não quebrar a página
        }
    }

    if ($request->filled('data_fim')) {
        try {
            $fim = \Carbon\Carbon::createFromFormat('d/m/Y', trim($request->data_fim))->endOfDay();
            $query->where('created_at', '<=', $fim);
        } catch (\Exception $e) {
            // Fallback silencioso
        }
    }

    // 3. Filtro de Central
    if ($request->filled('central_id')) {
        $query->whereHas('servidor', fn($q) => $q->where('central_id', $request->central_id));
    }

    // 4. Filtro de Órgão (Nova funcionalidade solicitada)
    if ($request->filled('orgao_id')) {
        $query->whereHas('servidor', fn($q) => $q->where('orgao_id', $request->orgao_id));
    }

    // 5. Filtro de Faixa de Nota (Conformidade)
    if ($request->filled('faixa_nota')) {
        if ($request->faixa_nota == 'alta') $query->where('nota_final', '>=', 80);
        if ($request->faixa_nota == 'media') $query->whereBetween('nota_final', [50, 79.9]);
        if ($request->faixa_nota == 'baixa') $query->where('nota_final', '<', 50);
    }

    $feedbacks = $query->latest()->get();

    // Ação de Exportar
    if ($request->action == 'exportar') {
        return $this->exportarRelatorioManual($feedbacks);
    }

    // Agrupamento para a tabela do Blade
    $dadosAgrupados = $feedbacks->groupBy(function($item) {
        return $item->servidor->orgao->orgao_nome ?? 'Não Informado';
    });

    return view('auditoria.relatorios', compact(
        'centrals', 
        'orgaos', 
        'feedbacks', 
        'dadosAgrupados', 
        'dataInicio', 
        'dataFim'
    ));
}

    private function exportarRelatorioManual($feedbacks)
    {
        $fileName = 'relatorio_geral_auditoria_' . date('d_m_Y_H_i') . '.csv';

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function () use ($feedbacks) {
            $file = fopen('php://output', 'w');

            // Adiciona o BOM para o Excel reconhecer acentos (ç, á, õ) corretamente
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Cabeçalho do CSV
            fputcsv($file, ['Orgao', 'Central', 'Servidor', 'Data', 'Nota Final (%)', 'Auditor'], ';');

            // Linhas de dados
            foreach ($feedbacks as $fb) {
                fputcsv($file, [
                    $fb->servidor->orgao->orgao_nome ?? 'N/A',
                    $fb->servidor->central->central_nome ?? 'N/A',
                    $fb->servidor->servidor_nome,
                    $fb->created_at->format('d/m/Y'),
                    number_format($fb->nota_final, 2, ',', '.'),
                    $fb->user->name ?? 'Sistema'
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
