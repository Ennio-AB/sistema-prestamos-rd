<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained('loans')->cascadeOnDelete();
            $table->integer('numero_cuota');
            $table->date('fecha_vencimiento');
            $table->decimal('monto', 12, 2)->comment('Monto de la cuota sin mora');
            $table->decimal('mora', 12, 2)->default(0)->comment('Monto de mora acumulada');
            $table->boolean('pagado')->default(false);
            $table->date('fecha_pago')->nullable()->comment('Fecha en que fue pagada');
            $table->timestamps();

            $table->unique(['loan_id', 'numero_cuota']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('installments');
    }
};
