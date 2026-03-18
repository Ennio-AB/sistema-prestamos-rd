<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'cedula',
        'telefono',
        'direccion',
        'email',
        'trabajo',
        'referencias',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function activeLoans()
    {
        return $this->hasMany(Loan::class)->whereIn('estado', ['activo', 'atrasado', 'en_cobranza']);
    }

    // Formato de cédula dominicana: 000-0000000-0
    public function getCedulaFormateadaAttribute(): string
    {
        $c = preg_replace('/\D/', '', $this->cedula);
        if (strlen($c) === 11) {
            return substr($c, 0, 3) . '-' . substr($c, 3, 7) . '-' . substr($c, 10);
        }
        return $this->cedula;
    }
}
