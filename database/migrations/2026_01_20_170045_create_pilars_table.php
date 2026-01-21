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
        Schema::create('pilars', function (Blueprint $table) {
            $table->id();
            // Use decimal para maior precisão em cálculos de auditoria
$table->decimal('pilar_value', 3, 2)->default(0); 
// O 3, 2 significa: 3 dígitos no total, sendo 2 após a vírgula (ex: 5.00)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pilars');
    }
};
