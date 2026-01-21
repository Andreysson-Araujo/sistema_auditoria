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
        Schema::create('servidors', function (Blueprint $table) {
            $table->id();
            $table->string('servidor_nome');

            //Chaves Estrangeiras
            $table->foreignId('central_id')->constrained('centrals')->onDelete('cascade');
            $table->foreignId('orgao_id')->constrained('orgaos')->onDelete('cascade');
            $table->foreignId('nivel_id')->constrained('nivels')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servidors');
    }
};
