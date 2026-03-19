<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('legal_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained('loans')->cascadeOnDelete();
            $table->unsignedBigInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('usuarios')->restrictOnDelete();
            $table->enum('tipo', [
                'notificacion',
                'llamada',
                'visita',
                'carta',
                'demanda',
                'acuerdo_pago',
                'otro'
            ]);
            $table->text('descripcion');
            $table->date('fecha');
            $table->string('resultado')->nullable()->comment('Resultado o respuesta del cliente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('legal_actions');
    }
};
