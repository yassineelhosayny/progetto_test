<?php

namespace App\Console\Commands;

use App\Jobs\GenerateFilamentResourceJob;
use App\Models\ResourceGenerator;
use Illuminate\Console\Command;

class TestResourceGeneratorJob extends Command
{
    protected $signature = 'test:resource-generator {id} {--now : Esegui il job ora (sincrono) invece di dispatchare in coda} {--queue : Forza dispatch sulla coda generators}';
    protected $description = 'Test del job GenerateFilamentResourceJob (dispatch su coda o esecuzione sincrona)';

    public function handle()
    {
        $id = $this->argument('id');
        
        try {
            $resource = ResourceGenerator::findOrFail($id);
            $this->info("Testing job con risorsa: {$resource->name} (ID: {$id})");

            $now = $this->option('now');
            $forceQueue = $this->option('queue');

            if ($now) {
                $this->info('Eseguo il job in modalità sincrona (--now)');
                $job = new GenerateFilamentResourceJob($resource);
                $job->handle();
                $this->info("✅ Job completato con successo!");
            } else {
                $this->info('Dispatching job sulla coda generators');
                GenerateFilamentResourceJob::dispatch($resource)->onQueue('generators');
                $this->info('✅ Job inviato alla coda (generators)');
            }
            
        } catch (\Exception $e) {
            $this->error("❌ Errore durante l'esecuzione del job:");
            $this->error($e->getMessage());
            $this->error("Stack trace:");
            $this->error($e->getTraceAsString());
            
            return 1;
        }
        
        return 0;
    }
}