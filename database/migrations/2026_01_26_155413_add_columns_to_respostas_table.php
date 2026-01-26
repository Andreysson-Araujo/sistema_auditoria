<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('respostas', function (Blueprint $table) {
            // Coluna para saber de qual servidor é a resposta
           

            // Coluna que você está tentando filtrar no erro (deve ser nullable)
            $table->foreignId('feedback_id')->nullable()->constrained('feedbacks');
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
