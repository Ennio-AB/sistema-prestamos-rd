<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'usuario_id',
        'monto',
        'interes',
        'tipo_interes',
        'plazo',
        'frecuencia',
        'fecha_inicio',
        'mora_porcentaje',
        'estado',
        'notas',
    ];

    protected function casts(): array
    {
        return [
            'monto'           => 'decimal:2',
            'interes'         => 'decimal:2',
            'mora_porcentaje' => 'decimal:2',
            'fecha_inicio'    => 'date',
        ];
    }

    // Relaciones
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function installments()
    {
        return $this->hasMany(Installment::class)->orderBy('numero_cuota');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function legalActions()
    {
        return $this->hasMany(LegalAction::class)->orderByDesc('fecha');
    }

    // Cálculos
    public function calcularCuota(): float
    {
        $tasaMensual = $this->tipo_interes === 'anual'
            ? $this->interes / 12
            : $this->interes;

        $interes = $this->monto * ($tasaMensual / 100) * $this->plazo;
        return ($this->monto + $interes) / $this->plazo;
    }

    public function getTotalPrestadoAttribute(): float
    {
        return (float) $this->monto;
    }

    public function getTotalInteresesAttribute(): float
    {
        $tasaMensual = $this->tipo_interes === 'anual'
            ? $this->interes / 12
            : $this->interes;
        return (float) ($this->monto * ($tasaMensual / 100) * $this->plazo);
    }

    public function getTotalAPagarAttribute(): float
    {
        return $this->total_prestado + $this->total_intereses;
    }

    public function getTotalPagadoAttribute(): float
    {
        return (float) $this->payments()->sum('monto');
    }

    public function getSaldoPendienteAttribute(): float
    {
        return $this->total_a_pagar - $this->total_pagado;
    }

    public function getCuotasVencidasAttribute()
    {
        return $this->installments()
            ->where('pagado', false)
            ->where('fecha_vencimiento', '<', now())
            ->get();
    }
}
