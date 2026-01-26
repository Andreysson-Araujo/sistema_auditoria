<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resposta extends Model
{
    use HasFactory;
    protected $fillable = ['servidor_id', 'pergunta_id', 'valor'];

    public function pergunta()
    {
        return $this->belongsTo(Pergunta::class, 'pergunta_id');
    }

    // Aproveite e adicione o do servidor também, se não tiver:
    public function servidor()
    {
        return $this->belongsTo(Servidor::class, 'servidor_id');
    }
}
