<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // La tabla 'usuarios' ya existe en la BD con datos; se crea solo si no existe
        if (!Schema::hasTable('usuarios')) {
            Schema::create('usuarios', function (Blueprint $table) {
                $table->id();
                $table->string('username', 50)->unique();
                $table->string('password');
                $table->string('nombre', 100);
                $table->string('email', 100)->nullable()->unique();
                $table->enum('rol', ['admin', 'empleado'])->default('empleado');
                $table->boolean('activo')->default(true);
                $table->rememberToken();
                $table->timestamp('fecha_registro')->useCurrent();
            });
        } else {
            // Agrega remember_token si no existe (necesario para Laravel Auth)
            if (!Schema::hasColumn('usuarios', 'remember_token')) {
                Schema::table('usuarios', function (Blueprint $table) {
                    $table->rememberToken()->nullable();
                });
            }
        }

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('usuarios');
    }
};
