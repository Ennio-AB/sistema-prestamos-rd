<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->unsignedInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('usuarios')->restrictOnDelete();
            $table->decimal('monto', 12, 2)->comment('Monto principal del préstamo en RD$');
            $table->decimal('interes', 5, 2)->comment('Tasa de interés en porcentaje');
            $table->enum('tipo_interes', ['mensual', 'anual'])->default('mensual');
            $table->integer('plazo')->comment('Número de cuotas');
            $table->enum('frecuencia', ['diario', 'semanal', 'quincenal', 'mensual'])->default('mensual');
            $table->date('fecha_inicio');
            $table->decimal('mora_porcentaje', 5, 2)->default(5.00)->comment('% de mora sobre cuota vencida');
            $table->enum('estado', ['activo', 'atrasado', 'en_cobranza', 'legal', 'cerrado'])->default('activo');
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
