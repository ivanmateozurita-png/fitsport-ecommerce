<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear roles
        Role::firstOrCreate(['name' => 'admin']);      // Acceso total: categorías, productos, usuarios, reportes
        Role::firstOrCreate(['name' => 'bodeguero', 'guard_name' => 'web']);  // Solo productos: agregar/editar/eliminar productos
        Role::firstOrCreate(['name' => 'client']);     // Cliente: solo compra y ve su historial

        // Asignar rol admin al primer usuario (ID 1) si existe
        $admin = User::find(1);
        if ($admin) {
            $admin->assignRole('admin');
        }

        // Asignar rol client a todos los demás usuarios
        User::where('id', '>', 1)->each(function ($user) {
            if (! $user->hasAnyRole(['admin', 'encargado', 'client'])) {
                $user->assignRole('client');
            }
        });
    }
}
