<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser; // ADICIONE ESTA LINHA
use Filament\Panel; // ADICIONE ESTA LINHA
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser // ADICIONE O "implements FilamentUser"
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ADICIONE ESTE MÉTODO PARA CONTROLAR O ACESSO
    public function canAccessPanel(Panel $panel): bool
    {
        // Aqui você diz que tanto admins quanto auditores podem logar no painel
        return in_array($this->role, ['admin', 'auditor']);
    }
}