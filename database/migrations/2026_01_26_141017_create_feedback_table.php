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

        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servidor_id')->constrained('servidors'); // Quem está sendo auditado
            $table->foreignId('user_id')->constrained('users'); // O Auditor que está logado
            $table->date('data_auditoria');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
