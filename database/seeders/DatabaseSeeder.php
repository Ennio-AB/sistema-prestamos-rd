<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'password' => bcrypt('admin123'),
                'nombre'   => 'Administrador',
                'email'    => 'admin@prestamo.com',
                'rol'      => 'admin',
                'activo'   => true,
            ]
        );
    }
}
