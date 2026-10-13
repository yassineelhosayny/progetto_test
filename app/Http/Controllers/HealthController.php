<?php

namespace App\Http\Controllers;

use App\Services\GeneratedFilesAutoloader;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HealthController extends Controller
{
    /**
     * Health check per il sistema Filament Resource Generator
     */
    public function generator(): JsonResponse
    {
        $storageMode = app()->environment('production') || !is_writable(app_path());
        $autoloaderActive = class_exists('App\Services\GeneratedFilesAutoloader');
        $storageWritable = is_writable(storage_path('app'));
        $generatedFilesExist = is_dir(storage_path('app/generated'));
        
        // Test autoloader se attivo
        $classCount = 0;
        if ($autoloaderActive && $generatedFilesExist) {
            try {
                GeneratedFilesAutoloader::register();
                $classMap = GeneratedFilesAutoloader::getClassMap();
                $classCount = count($classMap);
            } catch (\Exception $e) {
                // Silently fail
            }
        }
        
        // Determina status complessivo
        $status = 'healthy';
        $issues = [];
        
        if ($storageMode && !$storageWritable) {
            $status = 'error';
            $issues[] = 'Storage directory not writable';
        }
        
        if ($storageMode && !$autoloaderActive) {
            $status = 'warning';
            $issues[] = 'Autoloader not active in storage mode';
        }
        
        return response()->json([
            'status' => $status,
            'timestamp' => now()->toISOString(),
            'environment' => app()->environment(),
            'storage_mode' => $storageMode,
            'autoloader_active' => $autoloaderActive,
            'storage_writable' => $storageWritable,
            'generated_files_exist' => $generatedFilesExist,
            'generated_classes_count' => $classCount,
            'app_writable' => is_writable(app_path()),
            'issues' => $issues,
            'version' => '2.0-storage-approach'
        ], $status === 'error' ? 500 : 200);
    }

    public function db(): JsonResponse {
        try {
            DB::connection()->getPdo(); // controlla la connessione al db
            $status = 'ok';
            $code = 200;
        } catch (\Throwable $e) {
            $status = 'db_error';
            $code = 500;
        }

        return response()->json([
            'status' => $status, 
            'timestamp' => now()->toISOString(),
        ], $code);
    }
}