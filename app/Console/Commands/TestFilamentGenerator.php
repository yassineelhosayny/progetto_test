<?php

namespace App\Console\Commands;

use App\Models\ResourceGenerator;
use App\Jobs\GenerateFilamentResourceJob;
use App\Services\GeneratedFilesAutoloader;
use Illuminate\Console\Command;

class TestFilamentGenerator extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'test:filament-generator {name} {--cluster : Create with cluster (has_categories = true)} {--storage : Force storage mode} {--now : Esegui la generazione ora (sincrona) invece di dispatchare in coda}';

    /**
     * The console command description.
     */
    protected $description = 'Test the complete Filament resource generator system';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = $this->argument('name');
        $hasCategories = $this->option('cluster');
        $forceStorage = $this->option('storage');
        
        $type = $hasCategories ? 'cluster resource' : 'simple resource';
        $mode = $forceStorage ? 'storage mode' : 'auto-detection mode';
        
        $this->info("🚀 Testing Filament Generator");
        $this->info("📝 Resource: {$name}");
        $this->info("🎯 Type: {$type}");
        $this->info("💾 Mode: {$mode}");
        $this->newLine();
        
        // Create resource
        $this->info("Creating resource...");
        $resource = new ResourceGenerator([
            'name' => $name,
            'has_categories' => $hasCategories
        ]);
        $resource->save();

        $this->info("✅ Resource created with ID: {$resource->id}");

        $now = $this->option('now');

        // Dispatch or run now depending on option
        if ($now) {
            $this->info('Eseguo la generazione immediatamente (--now)');
            $job = new GenerateFilamentResourceJob($resource, $forceStorage);
            $job->handle();
        } else {
            $this->info('Dispatching job sulla coda generators');
            GenerateFilamentResourceJob::dispatch($resource, $forceStorage)->onQueue('generators');
            $this->info('✅ Job inviato alla coda (generators)');
        }
        
        $this->newLine();
        $this->info("🔍 Testing autoloader...");
        
        // Test autoloader
        GeneratedFilesAutoloader::register();
        $classMap = GeneratedFilesAutoloader::getClassMap();
        
        if (!empty($classMap)) {
            $this->info("✅ Found " . count($classMap) . " classes in storage");
            foreach ($classMap as $className => $filePath) {
                if (strpos($className, $name) !== false) {
                    $this->line("   🎯 {$className}");
                }
            }
        } else {
            $this->info("ℹ️  No classes found in storage (normal in development mode)");
        }
        
        $this->newLine();
        $this->info("🎉 Test completed successfully!");
        $this->info("💡 Check app/ directory for generated files (development mode)");
        if ($forceStorage) {
            $this->info("💡 Check storage/app/generated/ for storage mode files");
        }
        
        return 0;
    }
}