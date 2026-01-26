<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pergunta extends Model
{
    use HasFactory;

    protected $fillable = ['pilar_id', 'texto_pergunta', 'tipo'];

    public function pilar()
    {
        return $this->belongsTo(Pilar::class);
    }

    public function respostas()
    {
        return $this->hasMany(Resposta::class, 'pergunta_id');
    }
}
