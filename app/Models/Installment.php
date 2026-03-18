<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'numero_cuota',
        'fecha_vencimiento',
        'monto',
        'mora',
        'pagado',
        'fecha_pago',
    ];

    protected function casts(): array
    {
        return [
            'monto'             => 'decimal:2',
            'mora'              => 'decimal:2',
            'pagado'            => 'boolean',
            'fecha_vencimiento' => 'date',
            'fecha_pago'        => 'date',
        ];
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function getTotalAttribute(): float
    {
        return (float) ($this->monto + $this->mora);
    }

    public function getEstaVencidaAttribute(): bool
    {
        return !$this->pagado && $this->fecha_vencimiento->isPast();
    }

    public function calcularMora(): float
    {
        if (!$this->esta_vencida) {
            return 0;
        }
        return (float) ($this->monto * ($this->loan->mora_porcentaje / 100));
    }
}
