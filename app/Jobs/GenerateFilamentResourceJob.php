<?php

namespace App\Jobs;

use App\Models\ResourceGenerator;
use App\Jobs\Generators\ModelGenerator;
use App\Jobs\Generators\PolicyGenerator;
use App\Jobs\Generators\ClusterGenerator;
use App\Jobs\Generators\ResourceGenerator as FilamentResourceGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Throwable;

class GenerateFilamentResourceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $resourceId;
    private bool $useStorageMode;

    /**
     * Maximum number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     */
    public int $timeout = 1200;

    /**
     * Create a new job instance.
     */
    public function __construct(ResourceGenerator $resource, ?bool $useStorageMode = null)
    {
        $this->resourceId = $resource->id;
        $this->useStorageMode = $useStorageMode ?? $this->shouldUseStorageMode();
    }

    /**
     * Determina se usare storage mode (per produzione) o filesystem normale (development)
     */
    private function shouldUseStorageMode(): bool
    {
        return app()->environment('production') || !is_writable(app_path());
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Configurazioni per shared hosting
        set_time_limit(300); // 5 minuti
        ini_set('memory_limit', '512M');
        
        // Recupera il modello dall'ID. Se non esiste (es. cancellato) esci silenziosamente.
        $resource = ResourceGenerator::find($this->resourceId);
        if (!$resource) {
            Log::warning('[GenerateFilamentResourceJob] ResourceGenerator non trovato, id: ' . $this->resourceId);
            return;
        }
        // Imposta lo stato a 'running' se la colonna esiste
        try {
            if (Schema::hasColumn($resource->getTable(), 'generation_status')) {
                $resource->generation_status = 'running';
                $resource->generation_started_at = now();
                $resource->generation_error = null;
                $resource->save();
            }
        } catch (Throwable $e) {
            Log::warning('[GenerateFilamentResourceJob] Impossibile aggiornare generation_status -> running: ' . $e->getMessage());
        }
        
        $mode = $this->useStorageMode ? 'STORAGE' : 'FILESYSTEM';
        Log::info('[GenerateFilamentResourceJob] Inizio generazione risorsa sincrona', [
            'resource' => $resource->toArray(),
            'mode' => $mode
        ]);
        
        try {
            // Crea i generators nell'ordine corretto
            $generators = [
                new ModelGenerator($this->useStorageMode),          // Prima: Models + Migrations
                new PolicyGenerator($this->useStorageMode),         // Secondo: Policies + Permissions
                new ClusterGenerator($this->useStorageMode),        // Terzo: Clusters (se necessari)
                new FilamentResourceGenerator($this->useStorageMode), // Quarto: Risorse Filament
            ];
            
            // Esegui tutti i generators
            foreach ($generators as $generator) {
                $generator->generate($resource);
            }
            
            // Aggiorna stato a 'completed' se possibile
            try {
                if (Schema::hasColumn($resource->getTable(), 'generation_status')) {
                    $resource->generation_status = 'completed';
                    $resource->generation_finished_at = now();
                    $resource->save();
                }
            } catch (Throwable $e) {
                Log::warning('[GenerateFilamentResourceJob] Impossibile aggiornare generation_status -> completed: ' . $e->getMessage());
            }

            Log::info('[GenerateFilamentResourceJob] Generazione completata con successo');
            // Attempt to link generated files into app/ so resources are visible immediately
            try {
                $this->linkGeneratedFiles($resource);
            } catch (Throwable $e) {
                Log::warning('[GenerateFilamentResourceJob] Impossibile creare symlink per i file generati: ' . $e->getMessage());
            }
            
        } catch (\Throwable $e) {
            Log::error('[GenerateFilamentResourceJob] Errore durante la generazione: ' . $e->getMessage(), [
                'exception' => $e,
                'resource' => $resource->toArray(),
            ]);

            // Se esiste la colonna per lo stato della generazione, aggiornala
            try {
                if (Schema::hasColumn((new ResourceGenerator)->getTable(), 'generation_status')) {
                    $resource->generation_status = 'failed';
                    $resource->generation_error = substr($e->getMessage(), 0, 1000);
                    $resource->save();
                }
            } catch (Throwable $inner) {
                Log::warning('[GenerateFilamentResourceJob] Impossibile aggiornare lo stato del resource_generator', [
                    'error' => $inner->getMessage()
                ]);
            }

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        Log::error('[GenerateFilamentResourceJob] Job fallito', [
            'resource_id' => $this->resourceId,
            'error' => $exception->getMessage()
        ]);

        try {
            $resource = ResourceGenerator::find($this->resourceId);
            if ($resource && Schema::hasColumn($resource->getTable(), 'generation_status')) {
                $resource->generation_status = 'failed';
                $resource->generation_error = substr($exception->getMessage(), 0, 1000);
                $resource->save();
            }
        } catch (Throwable $e) {
            Log::warning('[GenerateFilamentResourceJob] Errore durante l\'aggiornamento dello stato fallito', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Create symlinks for the generated resource files into the app/ directory
     */
    private function linkGeneratedFiles(ResourceGenerator $resource): void
    {
        $storageBase = storage_path('app/generated');
        // Link Filament resource folder
        $resourceName = $resource->name;

        $filamentSource = $storageBase . DIRECTORY_SEPARATOR . 'Filament' . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . $resourceName;
        $filamentTarget = app_path('Filament' . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . $resourceName);

        if (is_dir($filamentSource)) {
            if (!is_dir($filamentTarget)) {
                mkdir($filamentTarget, 0755, true);
            }

            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($filamentSource, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($iterator as $item) {
                $relative = str_replace($filamentSource . DIRECTORY_SEPARATOR, '', $item->getPathname());
                $targetPath = $filamentTarget . DIRECTORY_SEPARATOR . $relative;

                if ($item->isDir()) {
                    if (!is_dir($targetPath)) {
                        mkdir($targetPath, 0755, true);
                    }
                    continue;
                }

                // File
                if (file_exists($targetPath)) {
                    if (is_link($targetPath)) {
                        @unlink($targetPath);
                    } else {
                        Log::warning('[GenerateFilamentResourceJob] File exists and is not a symlink, skipping: ' . $targetPath);
                        continue;
                    }
                }

                if (!@symlink($item->getPathname(), $targetPath)) {
                    Log::warning('[GenerateFilamentResourceJob] Failed to create symlink: ' . $targetPath);
                }
            }
        }

        // Link Model
        if (!empty($resource->model_class)) {
            $parts = explode('\\', $resource->model_class);
            $modelFile = end($parts) . '.php';
            $modelSource = $storageBase . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR . $modelFile;
            $modelTargetDir = app_path('Models');
            $modelTarget = $modelTargetDir . DIRECTORY_SEPARATOR . $modelFile;

            if (is_file($modelSource)) {
                if (!is_dir($modelTargetDir)) {
                    mkdir($modelTargetDir, 0755, true);
                }

                if (file_exists($modelTarget)) {
                    if (is_link($modelTarget)) {
                        @unlink($modelTarget);
                    } else {
                        Log::warning('[GenerateFilamentResourceJob] Model file exists and is not a symlink, skipping: ' . $modelTarget);
                        // don't overwrite real files
                        goto skip_policy_link;
                    }
                }

                if (!@symlink($modelSource, $modelTarget)) {
                    Log::warning('[GenerateFilamentResourceJob] Failed to symlink model: ' . $modelTarget);
                }
            }
        }

        skip_policy_link:

        // Link Policy
        if (!empty($resource->name)) {
            $policyFile = $resourceName . 'Policy.php';
            $policySource = $storageBase . DIRECTORY_SEPARATOR . 'Policies' . DIRECTORY_SEPARATOR . $policyFile;
            $policyTargetDir = app_path('Policies');
            $policyTarget = $policyTargetDir . DIRECTORY_SEPARATOR . $policyFile;

            if (is_file($policySource)) {
                if (!is_dir($policyTargetDir)) {
                    mkdir($policyTargetDir, 0755, true);
                }

                if (file_exists($policyTarget)) {
                    if (is_link($policyTarget)) {
                        @unlink($policyTarget);
                    } else {
                        Log::warning('[GenerateFilamentResourceJob] Policy file exists and is not a symlink, skipping: ' . $policyTarget);
                        return;
                    }
                }

                if (!@symlink($policySource, $policyTarget)) {
                    Log::warning('[GenerateFilamentResourceJob] Failed to symlink policy: ' . $policyTarget);
                }
            }
        }
    }
}