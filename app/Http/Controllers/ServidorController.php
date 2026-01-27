<?php

namespace App\Http\Controllers;

use App\Models\Servidor;
use App\Models\Pergunta;
use App\Models\Resposta;
use App\Models\Nivel;
use App\Models\Central;
use App\Models\Orgao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServidorController extends Controller
{
    /**
     * TELA 1: Identificacao
     */
    public function identificacao()
    {
        $nivels = Nivel::all();
        $centrals = Central::all();
        $orgaos = Orgao::all();
        
        // Retorna a view identificacao.blade.php
        return view('identificacao', compact('nivels', 'centrals', 'orgaos'));
    }

    /**
     * AÇÃO: Salva o servidor e vai para perguntas
     */
    public function store(Request $request)
    {
        // Cria o servidor com os dados do formulário
        $servidor = Servidor::create($request->all());
        
        // Guarda o ID na sessão para as perguntas saberem quem está respondendo
        session(['servidor_respondente_id' => $servidor->id]);

        // Redireciona para a rota que exibe a view de perguntas
        return redirect()->route('perguntas');
    }

    /**
     * TELA 2: Perguntas
     */
    public function perguntas()
    {
        // Se tentarem acessar sem se identificar, volta para a identificação
        if (!session()->has('servidor_respondente_id')) {
            return redirect()->route('auditoria.create');
        }

        $perguntas = Pergunta::all();
        return view('perguntas', compact('perguntas'));
    }

    /**
     * AÇÃO: Salva apenas as respostas
     */
    public function salvarRespostas(Request $request)
    {
        $servidorId = session('servidor_respondente_id');

        if (!$servidorId) {
            return redirect()->route('auditoria.create');
        }

        $request->validate([
            'respostas' => 'required|array',
        ]);

        DB::transaction(function () use ($request, $servidorId) {
            foreach ($request->respostas as $perguntaId => $valor) {
                if ($valor !== null && $valor !== '') {

                    $pergunta = Pergunta::find($perguntaId);
                    Resposta::create([
                        'servidor_id' => $servidorId,
                        'pergunta_id' => $perguntaId,
                        'pilar_id'    => $pergunta->pilar_id,
                        'valor'       => $valor,
                        'feedback_id' => null, // Deixamos nulo como você pediu
                    ]); 
                }
            }
        });

        // Limpa a sessão para um novo servidor poder responder no mesmo PC
        session()->forget('servidor_respondente_id');

        return redirect()->route('auditoria.create')->with('sucesso', 'Enviado para auditoria!');
    }
}