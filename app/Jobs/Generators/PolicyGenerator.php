<?php

namespace App\Jobs\Generators;

use App\Models\ResourceGenerator;
use Illuminate\Support\Facades\Log;

class PolicyGenerator extends BaseGenerator
{
    public function generate($resource): void
    {
        if (!$resource instanceof ResourceGenerator) {
            throw new \InvalidArgumentException('Resource must be an instance of ResourceGenerator');
        }

        // Genera policy per il modello principale
        $resourceName = str_replace(' ', '', ucwords(str_replace(['_', '-'], ' ', $resource->name)));
        $this->createPolicy($resourceName);

        // Se ha categorie, genera anche la policy per le categorie
        if ($resource->has_categories) {
            $categoriesModelName = $resourceName . 'Categories';
            $this->createPolicy($categoriesModelName);
        }
    }

    protected function createPolicy(string $modelName): void
    {
        $policyName = $modelName . 'Policy';
        $policyPath = $this->getBasePath("Policies/{$policyName}.php");
        
        if (!file_exists($policyPath)) {
            $template = require app_path('Jobs/Templates/PolicyTemplate.php');
            
            // Crea la variabile del modello (es: News -> $news)
            $modelVariable = lcfirst($modelName);
            
            $policyContent = str_replace([
                '{{POLICY_NAME}}',
                '{{MODEL_NAME}}',
                '{{RESOURCE_NAME}}',
                '{{MODEL_VARIABLE}}'
            ], [
                $policyName,
                $modelName,
                $modelName,
                $modelVariable
            ], $template);
            
            $this->writeFile($policyPath, $policyContent, 'Policy');
            
            // Genera le permissions Shield per questa risorsa
            $this->generateShieldPermissions($modelName);
        }
    }

    protected function generateShieldPermissions(string $modelName): void
    {
        try {
            Log::info('[PolicyGenerator] Generazione permission diretta nel database', ['model' => $modelName]);
            
            // Lista delle permission standard per ogni risorsa (formato Policy)
            $permissions = [
                "ViewAny:{$modelName}",
                "View:{$modelName}",
                "Create:{$modelName}",
                "Update:{$modelName}",
                "Delete:{$modelName}",
                "Restore:{$modelName}",
                "ForceDelete:{$modelName}",
                "ForceDeleteAny:{$modelName}",
                "RestoreAny:{$modelName}",
                "Replicate:{$modelName}",
                "Reorder:{$modelName}"
            ];
            
            $createdPermissions = [];
            
            foreach ($permissions as $permissionName) {
                // Crea la permission con il nome esatto (senza conversioni)
                $permission = \Spatie\Permission\Models\Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web'
                ]);
                
                $createdPermissions[] = $permission->name;
                Log::info('[PolicyGenerator] Permission creata', ['name' => $permission->name]);
            }
            
            Log::info('[PolicyGenerator] Permissions create completate', [
                'model' => $modelName,
                'permissions' => $createdPermissions
            ]);
            
            // Assegna le permission al ruolo admin se esiste
            $this->assignPermissionsToAdmin($modelName);
            
        } catch (\Exception $e) {
            Log::warning('[PolicyGenerator] Errore nella generazione permission Shield', [
                'model' => $modelName,
                'error' => $e->getMessage()
            ]);
        }
    }

    protected function assignPermissionsToAdmin(string $modelName): void
    {
        try {
            // Trova entrambi i ruoli admin e super_admin
            $roles = \Spatie\Permission\Models\Role::whereIn('name', ['admin', 'super_admin'])->get();
            if ($roles->isEmpty()) {
                Log::warning('[PolicyGenerator] Ruolo admin/super_admin non trovato per assegnazione permission');
                return;
            }

            // Lista delle permission da assegnare (formato Policy esatto)
            $permissions = [
                "ViewAny:{$modelName}",
                "View:{$modelName}",
                "Create:{$modelName}",
                "Update:{$modelName}",
                "Delete:{$modelName}",
                "Restore:{$modelName}",
                "ForceDelete:{$modelName}",
                "ForceDeleteAny:{$modelName}",
                "RestoreAny:{$modelName}",
                "Replicate:{$modelName}",
                "Reorder:{$modelName}"
            ];

            foreach ($roles as $role) {
                $assignedPermissions = [];
                foreach ($permissions as $permissionName) {
                    $permission = \Spatie\Permission\Models\Permission::where('name', $permissionName)->first();
                    if ($permission && !$role->hasPermissionTo($permission)) {
                        $role->givePermissionTo($permission);
                        $assignedPermissions[] = $permission->name;
                        Log::info('[PolicyGenerator] Permission assegnata', [
                            'permission' => $permission->name,
                            'role' => $role->name
                        ]);
                    }
                }
                Log::info('[PolicyGenerator] Assegnazione permissions completata', [
                    'model' => $modelName,
                    'role' => $role->name,
                    'assigned' => $assignedPermissions
                ]);
            }
        } catch (\Exception $e) {
            Log::warning('[PolicyGenerator] Errore nell\'assegnazione permission ad admin/super_admin', [
                'model' => $modelName,
                'error' => $e->getMessage()
            ]);
        }
    }
}