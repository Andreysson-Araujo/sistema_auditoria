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

    public function identificacao() {
        $nivels = Nivel::all();
        $centrals = Central::all();
        $orgaos = Orgao::all();
        return view('auditoria.identificacao', compact('nivels', 'centrals', 'orgaos'));
    }

    public function iniciar(Request $request) {
        $servidor = Servidor::create($request->all());
        return redirect()->route('auditoria.perguntas', $servidor->id);
    }

    public function perguntas($servidor_id) {
        $perguntas = Pergunta::all();
        return view('auditoria.perguntas', compact('perguntas', 'servidor_id'));
    }

    public function store(Request $request) {
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

    public function index() {
        $feedbacks = Feedback::with('servidor')->latest()->get();
        return view('auditoria.selecionar_servidor', compact('feedbacks'));
    }
}