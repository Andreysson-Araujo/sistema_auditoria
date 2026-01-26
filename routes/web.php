<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServidorController;
use App\Http\Controllers\FeedbackController;
use League\Csv\Query\Row;



// --- PARTE 1: SERVIDORES (Público) ---
// 1. Tela de Identificação (seu primeiro HTML)
Route::get('/identificacao', [ServidorController::class, 'identificacao'])->name('auditoria.create');

// 2. A rota que recebe os dados do servidor (Onde o erro estava ocorrendo)
Route::post('/servidores/salvar', [ServidorController::class, 'store'])->name('servidores.store');

// 3. A tela das perguntas
Route::get('/perguntas', [ServidorController::class, 'perguntas'])->name('perguntas');

// 4. O salvamento final das respostas
Route::post('/salvar-respostas', [ServidorController::class, 'salvarRespostas'])->name('auditoria.salvar');


// --- PARTE 2: AUDITORES (Restrito) ---
Route::middleware(['auth'])->group(function () {
    // Listagem para o auditor ver quem respondeu
    Route::get('/auditoria/painel', [FeedbackController::class, 'index'])->name('auditoria.index');
    // Ver detalhes de uma resposta específica
    Route::get('/auditoria/ver/{id}', [FeedbackController::class, 'show'])->name('auditoria.show');
});