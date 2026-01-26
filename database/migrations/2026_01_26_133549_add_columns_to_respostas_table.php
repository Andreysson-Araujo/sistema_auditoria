<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('respostas', function (Blueprint $table) {
            $table->foreignId('servidor_id')->constrained('servidors')->onDelete('cascade');
            $table->foreignId('pergunta_id')->constrained('perguntas');
            $table->text('valor'); // Salva nota, Sim/Não ou texto longo
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('respostas', function (Blueprint $table) {
            //
        });
    }
};
