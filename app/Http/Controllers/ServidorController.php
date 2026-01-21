<?php

namespace App\Http\Controllers;

use App\Models\Central;
use App\Models\Orgao;
use App\Models\Nivel;
use App\Models\Servidor;
use Illuminate\Http\Request;

class ServidorController extends Controller
{
    /**
     * Exibe o formulário de identificação do servidor.
     */
    public function index()
    {
        // Carrega os dados para preencher os campos de seleção no formulário
        $centrals = Central::all();
        $orgaos = Orgao::all();
        $nivels = Nivel::all();

        // Retorna a view 'servidores.blade.php' passando as variáveis
        return view('servidores', compact('centrals', 'orgaos', 'nivels'));
    }

    /**
     * Salva os dados do servidor no banco de dados.
     */
    public function store(Request $request)
    {
        // Validação básica para garantir que nenhum campo venha vazio
        $request->validate([
            'servidor_nome' => 'required|string|max:255',
            'central_id' => 'required|exists:centrals,id',
            'orgao_id' => 'required|exists:orgaos,id',
            'nivel_id' => 'required|exists:nivels,id',
        ]);

        // Cria o registro na tabela de servidores
        $servidor = Servidor::create([
            'servidor_nome' => $request->servidor_nome,
            'central_id'    => $request->central_id,
            'orgao_id'      => $request->orgao_id,
            'nivel_id'      => $request->nivel_id,
        ]);

        // Por enquanto, após salvar, ele apenas volta com uma mensagem de sucesso
        // No futuro, redirecionaremos para a página de perguntas passando o ID do servidor
        return redirect()->back()->with('success', 'Servidor cadastrado com sucesso!');
    }
}