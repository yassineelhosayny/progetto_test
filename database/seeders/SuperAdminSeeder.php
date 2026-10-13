<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        // Crea l'utente super admin (modifica email/password se necessario)
        $user = User::firstOrCreate(
            ['email' => 'access1@adverteaser.com'],
            [
                'name' => 'Admin CP',
                'lastname' => 'Admin CP',
                'password' => bcrypt('878373?Ent'), // Cambia la password!
            ]
        );

        // Crea il ruolo super_admin se non esiste
        $role = Role::firstOrCreate(['name' => 'super_admin']);

        // Assegna tutti i permessi al ruolo
        $role->syncPermissions(Permission::all());

        // Assegna il ruolo all'utente
        $user->assignRole($role);

        // Assegna direttamente tutti i permessi all'utente (opzionale)
        $user->syncPermissions(Permission::all());
    }
}
