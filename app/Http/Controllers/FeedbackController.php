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
    $query = Feedback::query()->with('servidor.orgao', 'servidor.central');

    // Filtros de Período
    if ($request->filled('data_inicio')) {
        $query->whereDate('created_at', '>=', $request->data_inicio);
    }
    if ($request->filled('data_fim')) {
        $query->whereDate('created_at', '<=', $request->data_fim);
    }

    // Filtros de Hierarquia
    if ($request->filled('central_id')) {
        $query->whereHas('servidor', fn($q) => $q->where('central_id', $request->central_id));
    }
    if ($request->filled('orgao_id')) {
        $query->whereHas('servidor', fn($q) => $q->where('orgao_id', $request->orgao_id));
    }

    $feedbacks = $query->get();

    // --- ESTATÍSTICAS ---
    $totalAuditorias = $feedbacks->count();
    $mediaGeral = $feedbacks->avg('nota_final');
    
    // Agrupamento por Órgão (Quantidade e Média)
    $porOrgao = $feedbacks->groupBy('servidor.orgao.orgao_nome')->map(function ($row) {
        return [
            'quantidade' => $row->count(),
            'media' => $row->avg('nota_final')
        ];
    });

    $centrals = \App\Models\Central::all();
    $orgaos = \App\Models\Orgao::all();

    return view('auditoria.relatorios', compact('totalAuditorias', 'mediaGeral', 'porOrgao', 'centrals', 'orgaos'));
}
}
