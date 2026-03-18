<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    public $timestamps = false;

    protected $fillable = [
        'username',
        'password',
        'nombre',
        'email',
        'rol',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'activo'   => 'boolean',
            'fecha_registro' => 'datetime',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    public function loans()
    {
        return $this->hasMany(Loan::class, 'usuario_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'usuario_id');
    }

    public function legalActions()
    {
        return $this->hasMany(LegalAction::class, 'usuario_id');
    }
}
