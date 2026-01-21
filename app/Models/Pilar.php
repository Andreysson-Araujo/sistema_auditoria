<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pilar extends Model
{
    use HasFactory;

    // Se no seu banco só existe a coluna pilar_value, deixe assim:
    protected $fillable = ['pilar_value']; 

    protected $casts = [
        'pilar_value' => 'float',
    ];

    public function perguntas() {
        return $this->hasMany(Pergunta::class);
    }
}