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
    ];

    // Relacionamento com o Servidor
    public function servidor()
    {
        return $this->belongsTo(Servidor::class);
    }

    // Relacionamento com o Auditor (User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}