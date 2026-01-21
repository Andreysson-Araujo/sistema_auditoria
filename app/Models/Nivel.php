<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Nivel extends Model
{
    use HasFactory;

    // Nome da tabela (opcional se seguir o padrão, mas bom garantir)
    protected $table = 'nivels';

    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'nivel_nome',
    ];

    /**
     * Relacionamento: Um Nível tem muitos Servidores.
     * (Você usará isso assim que criar a Model Servidor)
     */
    public function servidores(): HasMany
    {
        return $this->hasMany(Servidor::class);
    }
    
}