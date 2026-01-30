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
    public function index()
    {
        $feedbacks = Feedback::with('servidor.orgao')->latest()->get();
        return view('auditoria.selecionar_servidor', compact('feedbacks'));
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
            ->get();

        return view('auditoria.pendentes', compact('servidores'));
    }

    public function finalizarAuditoria(Request $request, $servidor_id)
    {
        DB::transaction(function () use ($servidor_id) {
            $feedback = Feedback::create([
                'servidor_id' => $servidor_id,
                'user_id'     => auth()->id(),
                'data_auditoria' => now(),
            ]);

            $respostas = Resposta::where('servidor_id', $servidor_id)
                ->whereNull('feedback_id')
                ->get();

            foreach ($respostas as $resp) {
                $resp->update(['feedback_id' => $feedback->id]);
            }

            // --- NOVO CÁLCULO PARA NOTAS 1 A 5 ---
            $totalPerguntas = $respostas->count();

            // Somamos os valores das respostas (ex: 5 + 4 + 3...)
            $somaNotas = $respostas->sum(function ($r) {
                return (int) $r->valor;
            });

            // O máximo que ele poderia tirar (Ex: 10 perguntas x nota 5 = 50)
            $pontuacaoMaxima = $totalPerguntas * 5;

            // Transformamos em porcentagem (Ex: 40 pontos de 50 = 80%)
            $notaFinal = $pontuacaoMaxima > 0 ? ($somaNotas / $pontuacaoMaxima) * 100 : 0;

            $feedback->update(['nota_final' => $notaFinal]);

            return $feedback;
        });

        return redirect()->route('auditoria.index')->with('sucesso', 'Auditoria calculada com sucesso!');
    }
}
