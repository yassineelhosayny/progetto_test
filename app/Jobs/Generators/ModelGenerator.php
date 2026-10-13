<?php

namespace App\Jobs\Generators;

use App\Models\ResourceGenerator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class ModelGenerator extends BaseGenerator
{
    public function generate($resource): void
    {
        if (!$resource instanceof ResourceGenerator) {
            throw new \InvalidArgumentException('Resource must be an instance of ResourceGenerator');
        }

        // Genera il modello principale
        $resourceName = str_replace(' ', '', ucwords(str_replace(['_', '-'], ' ', $resource->name)));
        $this->createModel($resourceName);

        // Se ha categorie, genera anche il modello categorie
        if ($resource->has_categories) {
            $categoriesModelName = $resourceName . 'Categories';
            $this->createModel($categoriesModelName);
        }
    }

    protected function createModel(string $modelName): void
    {
        $modelPath = $this->getBasePath("Models/{$modelName}.php");
        
        if (!file_exists($modelPath)) {
            $template = require app_path('Jobs/Templates/ModelTemplate.php');
            
            // Genera il nome della tabella: plurale snake_case del nome del modello
            $tableName = \Illuminate\Support\Str::snake($modelName);
            
            $modelContent = str_replace([
                '{{MODEL_NAME}}',
                '{{TABLE_NAME}}'
            ], [
                $modelName,
                $tableName
            ], $template);
            
            $this->writeFile($modelPath, $modelContent, 'Model');
            
            // Crea anche la migrazione
            $this->createMigration($modelName);
        }
    }

    protected function createMigration(string $modelName): void
    {
        $tableName = \Illuminate\Support\Str::snake($modelName);
        
        // Controlla se la tabella esiste già
        if (Schema::hasTable($tableName)) {
            Log::info('[ModelGenerator] Tabella già esistente, skip migrazione', ['table' => $tableName]);
            return;
        }
        
        $timestamp = date('Y_m_d_His');
        $migrationName = "create_{$tableName}_table";
        $migrationPath = base_path("database/migrations/{$timestamp}_{$migrationName}.php");
        
        $template = require app_path('Jobs/Templates/MigrationTemplate.php');
        $migrationContent = str_replace('{{TABLE_NAME}}', $tableName, $template);
        
        file_put_contents($migrationPath, $migrationContent);
        Log::info('[ModelGenerator] Migrazione creata', ['table' => $tableName, 'path' => $migrationPath]);
        
        // Esegui la migrazione
        try {
            Artisan::call('migrate', ['--force' => true]);
            Log::info('[ModelGenerator] Migrazione eseguita con successo', ['table' => $tableName]);
        } catch (\Exception $e) {
            Log::error('[ModelGenerator] Errore nell\'esecuzione della migrazione', ['error' => $e->getMessage()]);
        }
    }
}