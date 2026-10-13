<?php

namespace App\Services;

use Illuminate\Filesystem\Filesystem;

class GeneratedFilesAutoloader
{
    private static bool $registered = false;
    private static array $classMap = [];

    /**
     * Registra l'autoloader per i file generati
     */
    public static function register(): void
    {
        if (self::$registered) {
            return;
        }

        // Register the autoloader but avoid performing filesystem operations
        // at registration time (these can run too early during composer/package discovery).
        spl_autoload_register([self::class, 'autoload']);
        self::$registered = true;
    }

    /**
     * Autoloader function
     */
    public static function autoload(string $className): bool
    {
        // Aggiorna la class map se necessario
        if (empty(self::$classMap)) {
            // Lazy-update: do it only when autoload is actually invoked.
            try {
                self::updateClassMap();
            } catch (\Throwable $e) {
                // Silently fail during early bootstrap
                return false;
            }
        }

        if (isset(self::$classMap[$className])) {
            $filePath = self::$classMap[$className];
            try {
                $fs = new Filesystem();
                if ($fs->exists($filePath)) {
                    require_once $filePath;
                    return true;
                }
            } catch (\Throwable $e) {
                return false;
            }
        }

        return false;
    }

    /**
     * Aggiorna la mappa delle classi generate
     */
    public static function updateClassMap(): void
    {
        $storagePath = storage_path('app/generated');

        // Avoid using the Facade here; use native checks and a local Filesystem instance.
        if (!is_dir($storagePath)) {
            return;
        }

        try {
            self::$classMap = [];
            self::scanDirectory($storagePath, 'App');
        } catch (\Throwable $e) {
            // If filesystem isn't ready or permissions prevent reading, silently skip.
            self::$classMap = [];
            return;
        }
    }

    /**
     * Scansiona ricorsivamente una directory per trovare classi PHP
     */
    private static function scanDirectory(string $directory, string $namespace): void
    {
        $fs = new Filesystem();
        $items = $fs->allFiles($directory);

        foreach ($items as $file) {
            try {
                if ($file->getExtension() !== 'php') {
                    continue;
                }

                $relativePath = str_replace($directory . DIRECTORY_SEPARATOR, '', $file->getPathname());
                $relativePath = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $relativePath);
                
                // Converte il path in namespace
                $className = $namespace . '\\' . str_replace(
                    [DIRECTORY_SEPARATOR, '.php'],
                    ['\\', ''],
                    $relativePath
                );

                self::$classMap[$className] = $file->getPathname();
            } catch (\Throwable $e) {
                // Skip problematic files
                continue;
            }
        }
    }

    /**
     * Ottieni la class map corrente
     */
    public static function getClassMap(): array
    {
        return self::$classMap;
    }

    /**
     * Verifica se una classe è gestita dall'autoloader
     */
    public static function hasClass(string $className): bool
    {
        if (empty(self::$classMap)) {
            self::updateClassMap();
        }

        return isset(self::$classMap[$className]);
    }
}