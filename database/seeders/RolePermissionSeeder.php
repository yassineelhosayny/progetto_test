<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crea i ruoli base
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $editor = Role::firstOrCreate(['name' => 'editor']);

        // Permessi per le pagine con formato Policy
        $pagePermissions = [
            'ViewAny:Page',
            'View:Page',
            'Create:Page',
            'Update:Page',
            'Delete:Page',
            'Restore:Page',
            'ForceDelete:Page',
            'ForceDeleteAny:Page',
            'RestoreAny:Page',
            'Replicate:Page',
            'Reorder:Page',
        ];

        // Permessi per le news con formato Policy
        $newsPermissions = [
            'ViewAny:News',
            'View:News',
            'Create:News',
            'Update:News',
            'Delete:News',
            'Restore:News',
            'ForceDelete:News',
            'ForceDeleteAny:News',
            'RestoreAny:News',
            'Replicate:News',
            'Reorder:News',
        ];

        // Permessi per le news categories
        $newsCategoryPermissions = [
            'ViewAny:NewsCategory',
            'View:NewsCategory',
            'Create:NewsCategory',
            'Update:NewsCategory',
            'Delete:NewsCategory',
            'Restore:NewsCategory',
            'ForceDelete:NewsCategory',
            'ForceDeleteAny:NewsCategory',
            'RestoreAny:NewsCategory',
            'Replicate:NewsCategory',
            'Reorder:NewsCategory',
        ];

        // Permessi per i progetti con formato Policy
        $projectPermissions = [
            'ViewAny:Project',
            'View:Project',
            'Create:Project',
            'Update:Project',
            'Delete:Project',
            'Restore:Project',
            'ForceDelete:Project',
            'ForceDeleteAny:Project',
            'RestoreAny:Project',
            'Replicate:Project',
            'Reorder:Project',
        ];

        // Permessi per i clienti delle video demo
        $clientPermissions = [
            'ViewAny:Client',
            'View:Client',
            'Create:Client',
            'Update:Client',
            'Delete:Client',
            'Restore:Client',
            'ForceDelete:Client',
            'ForceDeleteAny:Client',
            'RestoreAny:Client',
            'Replicate:Client',
            'Reorder:Client',
        ];

        // Permessi per i menu con formato Policy
        $menuPermissions = [
            'ViewAny:Menu',
            'View:Menu',
            'Create:Menu',
            'Update:Menu',
            'Delete:Menu',
            'Restore:Menu',
            'ForceDelete:Menu',
            'ForceDeleteAny:Menu',
            'RestoreAny:Menu',
            'Replicate:Menu',
            'Reorder:Menu',
        ];

        // Permessi per gli utenti con formato Policy
        $userPermissions = [
            'ViewAny:User',
            'View:User',
            'Create:User',
            'Update:User',
            'Delete:User',
            'Restore:User',
            'ForceDelete:User',
            'ForceDeleteAny:User',
            'RestoreAny:User',
            'Replicate:User',
            'Reorder:User',
        ];

        // Permessi per i ruoli
        $rolePermissions = [
            'ViewAny:Role',
            'View:Role',
            'Create:Role',
            'Update:Role',
            'Delete:Role',
            'Restore:Role',
            'ForceDelete:Role',
            'ForceDeleteAny:Role',
            'RestoreAny:Role',
            'Replicate:Role',
            'Reorder:Role',
        ];

        // Crea tutti i permessi
        $allPermissions = array_merge(
            $pagePermissions, 
            $newsPermissions, 
            $newsCategoryPermissions,
            $projectPermissions,
            $clientPermissions,
            $menuPermissions, 
            $userPermissions,
            $rolePermissions
        );
        
        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assegna permessi ai ruoli
        $superAdmin->givePermissionTo($allPermissions); // Super admin ha tutti i permessi
        
        $admin->givePermissionTo([
            ...$pagePermissions,
            ...$newsPermissions,
            ...$newsCategoryPermissions,
            ...$projectPermissions,
            ...$clientPermissions,
            ...$menuPermissions,
            'ViewAny:User',
            'View:User',
            'Update:User',
        ]);
        
        $editor->givePermissionTo([
            ...$pagePermissions,
            ...$newsPermissions,
            ...$newsCategoryPermissions,
            ...$projectPermissions,
            'ViewAny:Menu',
            'View:Menu',
        ]);

        // Crea utente super admin se non esiste
        $superAdminUser = User::firstOrCreate([
            'email' => 'admincp@adverteaser.com'
        ], [
            'name' => 'Super Admin',
            'password' => bcrypt('adverteaser'),
        ]);

        $superAdminUser->assignRole('super_admin');

        // Crea utente admin se non esiste
        $adminUser = User::firstOrCreate([
            'email' => 'admin@natura.com'
        ], [
            'name' => 'Admin Natura',
            'password' => bcrypt('password'),
        ]);

        $adminUser->assignRole('admin');
    }
}
