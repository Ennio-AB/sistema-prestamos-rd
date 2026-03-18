<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'installment_id',
        'usuario_id',
        'monto',
        'fecha_pago',
        'metodo',
        'referencia',
        'notas',
    ];

    protected function casts(): array
    {
        return [
            'monto'      => 'decimal:2',
            'fecha_pago' => 'date',
        ];
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function installment()
    {
        return $this->belongsTo(Installment::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
