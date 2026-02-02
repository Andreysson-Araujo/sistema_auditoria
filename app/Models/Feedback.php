<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    // Força o Laravel a usar o nome correto da tabela
    protected $table = 'feedbacks';

    protected $fillable = [
        'servidor_id',
        'user_id',
        'data_auditoria',
        'nota_final',
        'comentario'
    ];

    // Relacionamento com o Servidor
    public function servidor()
    {
        return $this->belongsTo(Servidor::class);
    }

    public function respostas()
    {
        // O segundo parâmetro é a chave estrangeira na tabela 'respostas'
        return $this->hasMany(Resposta::class, 'feedback_id');
    }

    // Relacionamento com o Auditor (User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}