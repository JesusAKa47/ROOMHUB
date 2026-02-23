<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Crea el primer usuario administrador si no existe ninguno.
     * Ejecutar: php artisan db:seed --class=AdminSeeder
     */
    public function run(): void
    {
        if (User::where('role', User::ROLE_ADMIN)->exists()) {
            return;
        }

        $email = config('app.admin_email', 'admin@roomhub.local');
        $password = config('app.admin_password', 'password');

        User::create([
            'name' => 'Administrador',
            'email' => $email,
            'password' => Hash::make($password),
            'role' => User::ROLE_ADMIN,
        ]);

        $this->command->info("Usuario admin creado: {$email} (cambia la contraseña tras el primer acceso).");
    }
}
