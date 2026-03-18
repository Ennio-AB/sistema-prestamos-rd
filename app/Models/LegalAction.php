<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegalAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'usuario_id',
        'tipo',
        'descripcion',
        'fecha',
        'resultado',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
        ];
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public static function tipos(): array
    {
        return [
            'notificacion' => 'Notificación',
            'llamada'      => 'Llamada telefónica',
            'visita'       => 'Visita domiciliaria',
            'carta'        => 'Carta formal',
            'demanda'      => 'Demanda legal',
            'acuerdo_pago' => 'Acuerdo de pago',
            'otro'         => 'Otro',
        ];
    }
}
