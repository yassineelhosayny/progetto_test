<?php

namespace App\Observers;

use App\Models\ResourceGenerator;
use App\Jobs\GenerateFilamentResourceJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Throwable;

class ResourceGeneratorObserver
{
    public function created(ResourceGenerator $resource)
    {
        Log::info('[ResourceGeneratorObserver] Dispatching generation job for resource', ['resource' => $resource->toArray()]);

        try {
            // Se la tabella ha colonne di stato, imposta 'pending'
            try {
                if (Schema::hasColumn($resource->getTable(), 'generation_status')) {
                    $resource->generation_status = 'pending';
                    $resource->generation_started_at = null;
                    $resource->generation_finished_at = null;
                    $resource->generation_error = null;
                    $resource->save();
                }
            } catch (Throwable $e) {
                Log::warning('[ResourceGeneratorObserver] Impossibile aggiornare lo stato di generation_status (colonna mancante?): ' . $e->getMessage());
            }

            // Dispatch the job to the queue (uses the job's dispatchable trait)
            GenerateFilamentResourceJob::dispatch($resource)->onQueue('generators');

            Log::info('[ResourceGeneratorObserver] Job dispatched', ['resource_id' => $resource->id]);

        } catch (Throwable $e) {
            Log::error('[ResourceGeneratorObserver] Errore durante il dispatch del job di generazione', [
                'resource_id' => $resource->id,
                'resource_name' => $resource->name,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Rethrow to keep behavior consistent with observer (fail the operation)
            throw new \Exception("Impossibile dispatchare il job di generazione Filament: " . $e->getMessage(), 0, $e);
        }
    }
}
