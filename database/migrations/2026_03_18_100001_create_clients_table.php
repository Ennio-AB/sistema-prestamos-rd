<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('cedula', 11)->unique()->comment('Cédula de identidad dominicana (11 dígitos)');
            $table->string('telefono', 15)->nullable();
            $table->string('direccion')->nullable();
            $table->string('email', 100)->nullable();
            $table->string('trabajo')->nullable()->comment('Lugar de trabajo o actividad económica');
            $table->text('referencias')->nullable()->comment('Referencias personales o familiares');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
