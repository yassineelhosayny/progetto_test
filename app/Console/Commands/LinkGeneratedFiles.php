<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class LinkGeneratedFiles extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'filament:link-generated {--force : Force the operation to run when in production}';

    /**
     * The console command description.
     */
    protected $description = 'Create symbolic links for generated files from storage to app directory';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $storagePath = storage_path('app/generated');
        $appPath = app_path();

        if (!File::exists($storagePath)) {
            $this->error("Storage directory {$storagePath} does not exist!");
            return 1;
        }

        $this->info('Creating symbolic links for generated files...');

        $this->linkDirectory($storagePath, $appPath, '');

        $this->info('✅ Symbolic links created successfully!');
        $this->newLine();
        $this->info('💡 Now your IDE can find the generated files for autocompletion and navigation.');
        
        return 0;
    }

    /**
     * Crea link simbolici ricorsivamente
     */
    private function linkDirectory(string $sourceDir, string $targetDir, string $relativePath): void
    {
        $items = File::directories($sourceDir);
        $items = array_merge($items, File::files($sourceDir));

        foreach ($items as $item) {
            $itemName = basename($item);
            $sourcePath = $item;
            $targetPath = $targetDir . DIRECTORY_SEPARATOR . $relativePath . $itemName;
            $relativeSourcePath = $relativePath . $itemName;

            if (File::isDirectory($item)) {
                // È una directory
                if (!File::exists($targetPath)) {
                    File::makeDirectory($targetPath, 0755, true);
                    $this->line("📁 Created directory: app/{$relativeSourcePath}");
                }
                
                // Ricorsione per subdirectory
                $this->linkDirectory(
                    $sourcePath, 
                    $targetDir, 
                    $relativePath . $itemName . DIRECTORY_SEPARATOR
                );
            } else {
                // È un file
                if (File::exists($targetPath)) {
                    if (is_link($targetPath)) {
                        File::delete($targetPath);
                        $this->line("🔄 Removed existing symlink: app/{$relativeSourcePath}");
                    } else {
                        $this->warn("⚠️  File exists (not a symlink): app/{$relativeSourcePath}");
                        continue;
                    }
                }

                // Crea il symlink
                if ($this->createSymlink($sourcePath, $targetPath)) {
                    $this->line("🔗 Linked: app/{$relativeSourcePath}");
                } else {
                    $this->error("❌ Failed to link: app/{$relativeSourcePath}");
                }
            }
        }
    }

    /**
     * Crea un symbolic link cross-platform
     */
    private function createSymlink(string $target, string $link): bool
    {
        try {
            if (PHP_OS_FAMILY === 'Windows') {
                // Su Windows usa mklink
                $cmd = sprintf('mklink "%s" "%s"', $link, $target);
                exec($cmd, $output, $returnCode);
                return $returnCode === 0;
            } else {
                // Su Unix/Linux usa symlink
                return symlink($target, $link);
            }
        } catch (\Exception $e) {
            return false;
        }
    }
}