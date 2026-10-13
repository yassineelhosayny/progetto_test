<?php

namespace App\Console\Commands;

use App\Services\GeneratedFilesAutoloader;
use Illuminate\Console\Command;

class TestAutoloader extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'test:autoloader';

    /**
     * The console command description.
     */
    protected $description = 'Test the generated files autoloader';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔍 Testing Generated Files Autoloader...');
        
        // Registra l'autoloader
        GeneratedFilesAutoloader::register();
        $this->info('✅ Autoloader registered');
        
        // Ottieni la class map
        $classMap = GeneratedFilesAutoloader::getClassMap();
        $this->info('📋 Found ' . count($classMap) . ' classes in storage:');
        
        foreach ($classMap as $className => $filePath) {
            $this->line("   🎯 {$className}");
            $this->line("      📂 " . str_replace(base_path() . DIRECTORY_SEPARATOR, '', $filePath));
        }
        
        // Testa se riesce a trovare le classi TestStorage
        $testClasses = [
            'App\Models\TestStorage',
            'App\Policies\TestStoragePolicy',
            'App\Filament\Resources\TestStorage\TestStorageResource'
        ];
        
        $this->newLine();
        $this->info('🧪 Testing class detection:');
        
        foreach ($testClasses as $className) {
            $hasClass = GeneratedFilesAutoloader::hasClass($className);
            $icon = $hasClass ? '✅' : '❌';
            $this->line("   {$icon} {$className}");
        }
        
        // Testa il caricamento effettivo
        $this->newLine();
        $this->info('⚡ Testing class loading:');
        
        try {
            // Questo dovrebbe caricare la classe tramite autoloader
            if (class_exists('App\Models\TestStorage')) {
                $this->line('   ✅ App\Models\TestStorage loaded successfully');
            } else {
                $this->line('   ❌ App\Models\TestStorage failed to load');
            }
        } catch (\Exception $e) {
            $this->error('   ❌ Error loading class: ' . $e->getMessage());
        }
        
        return 0;
    }
}