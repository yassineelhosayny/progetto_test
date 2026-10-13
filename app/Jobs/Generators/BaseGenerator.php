<?php

namespace App\Jobs\Generators;

use Illuminate\Support\Facades\Log;

abstract class BaseGenerator
{
    protected bool $useStorageMode;

    public function __construct(?bool $useStorageMode = null)
    {
        $this->useStorageMode = $useStorageMode ?? $this->shouldUseStorageMode();
    }

    /**
     * Determina se usare storage mode (per produzione) o filesystem normale (development)
     */
    protected function shouldUseStorageMode(): bool
    {
        return app()->environment('production') || !is_writable(app_path());
    }

    /**
     * Ottieni il path base per i file generati
     */
    protected function getBasePath(string $subPath = ''): string
    {
        if ($this->useStorageMode) {
            return storage_path('app/generated/' . ltrim($subPath, '/'));
        }
        
        return app_path($subPath);
    }

    /**
     * Assicurati che la directory esista
     */
    protected function ensureDirectory(string $path): void
    {
        $directory = dirname($path);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
    }

    /**
     * Scrivi file con log
     */
    protected function writeFile(string $path, string $content, string $type = 'file'): void
    {
        $this->ensureDirectory($path);
        file_put_contents($path, $content);
        
        Log::info("[{$this->getLogPrefix()}] {$type} creato", [
            'path' => $path,
            'mode' => $this->useStorageMode ? 'storage' : 'filesystem'
        ]);
    }

    /**
     * Prefix per i log di questa classe
     */
    protected function getLogPrefix(): string
    {
        return class_basename(static::class);
    }

    /**
     * Metodo principale che ogni generator deve implementare
     */
    abstract public function generate($resource): void;
}