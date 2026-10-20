<?php

namespace Database\Seeders;

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

        // Conserva soltanto i due ruoli amministrativi previsti dalla videodemo.
        Role::query()
            ->whereNotIn('name', ['super_admin', 'admin'])
            ->get()
            ->each(fn (Role $role) => $role->delete());

        // La nuova gestione è basata sui ruoli: elimina i vecchi permessi
        // assegnati direttamente ai singoli utenti dal precedente form.
        User::query()->each(
            fn (User $user) => $user->syncPermissions([])
        );

        // Crea i due ruoli amministrativi definitivi.
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);

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
        ];

        // Permessi per la gestione delle video demo nel pannello Filament.
        $videoDemoPermissions = [
            'ViewAny:VideoDemo',
            'View:VideoDemo',
            'Create:VideoDemo',
            'Update:VideoDemo',
            'Delete:VideoDemo',
        ];

        // Permessi per i video gestiti all'interno di ogni contenitore.
        $videoPermissions = [
            'ViewAny:Video',
            'View:Video',
            'Create:Video',
            'Update:Video',
            'Delete:Video',
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
            $videoDemoPermissions,
            $videoPermissions,
            $menuPermissions, 
            $userPermissions,
            $rolePermissions
        );
        
        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assegna permessi ai ruoli
        // syncPermissions rende il risultato ripetibile e rimuove eventuali
        // permessi vecchi non più previsti per questi ruoli.
        $superAdmin->syncPermissions($allPermissions);
        
        $admin->syncPermissions([
            ...$pagePermissions,
            ...$newsPermissions,
            ...$newsCategoryPermissions,
            ...$projectPermissions,
            ...$clientPermissions,
            ...$videoDemoPermissions,
            ...$videoPermissions,
            ...$menuPermissions,
            'ViewAny:User',
            'View:User',
            'Update:User',
        ]);
        
        // Elimina dalla cache qualsiasi riferimento ai ruoli e permessi rimossi.
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
