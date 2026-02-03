<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            // Adiciona a coluna para armazenar -10, -5, 0, 5, 10
            // Colocamos default 0 para não quebrar os registros antigos
            $table->integer('ajuste_auditor')->default(0)->after('nota_final');
        });
    }

    public function down(): void
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->dropColumn('ajuste_auditor');
        });
    }
};