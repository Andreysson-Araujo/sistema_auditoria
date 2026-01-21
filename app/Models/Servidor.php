<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servidor extends Model
{
    use HasFactory;

    protected $table = 'servidors';

    protected $fillable = [
        'servidor_nome',
        'central_id',
        'orgao_id',
        'nivel_id',
    ];

    // Relacionamentos
    public function central() { return $this->belongsTo(Central::class); }
    public function orgao() { return $this->belongsTo(Orgao::class); }
    public function nivel() { return $this->belongsTo(Nivel::class); }
}