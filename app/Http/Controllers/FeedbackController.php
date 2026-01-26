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
        $feedbacks = Feedback::with('servidor')->latest()->get();
        return view('auditoria.selecionar_servidor', compact('feedbacks'));
    }

    public function auditoriasPendentes()
    {
        // Busca servidores que têm respostas onde o feedback_id é nulo
        // Usamos o withCount para saber quantas perguntas eles responderam
        $servidores = Servidor::whereHas('respostas', function ($query) {
            $query->whereNull('feedback_id');
        })->withCount(['respostas' => function ($query) {
            $query->whereNull('feedback_id');
        }])->get();

        return view('auditoria.pendentes', compact('servidores'));
    }

    public function finalizarAuditoria(Request $request, $servidor_id)
{
    DB::transaction(function () use ($servidor_id) {
        // 1. Cria o Feedback oficial
        $feedback = Feedback::create([
            'servidor_id' => $servidor_id,
            'user_id'     => auth()->id(), // ID do Auditor logado
            'data_auditoria' => now(),
        ]);

        // 2. Atualiza todas as respostas desse servidor que estavam "órfãs"
        // vinculando-as ao novo feedback_id
        Resposta::where('servidor_id', $servidor_id)
                ->whereNull('feedback_id')
                ->update(['feedback_id' => $feedback->id]);
    });

    return redirect()->route('auditoria.index')->with('sucesso', 'Auditoria finalizada com sucesso!');
}
}
