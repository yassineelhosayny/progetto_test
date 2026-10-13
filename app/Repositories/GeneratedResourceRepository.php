<?php

namespace App\Repositories;

use App\Models\ResourceGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

interface GeneratedResourceRepositoryInterface
{
    public function findBySlug(string $slug): ?ResourceGenerator;
    public function getAllPublic(): Collection;
    public function findRecordBySlug(ResourceGenerator $generator, string $slug): ?Model;
    public function invalidateCache(string $slug = null): void;
}

class GeneratedResourceRepository implements GeneratedResourceRepositoryInterface
{
    /**
     * Trova una risorsa generata per slug con cache
     */
    public function findBySlug(string $slug): ?ResourceGenerator
    {
        return Cache::remember("resource_{$slug}", 3600, function () use ($slug) {
            return ResourceGenerator::where('slug', $slug)
                ->where('is_public', true)
                ->first();
        });
    }

    /**
     * Ottieni tutte le risorse pubbliche con cache
     */
    public function getAllPublic(): Collection
    {
        return Cache::remember('all_public_resources', 3600, function () {
            return ResourceGenerator::public()
                ->orderBy('name')
                ->get();
        });
    }

    /**
     * Trova un record specifico di una risorsa generata
     */
    public function findRecordBySlug(ResourceGenerator $generator, string $slug): ?Model
    {
        try {
            $modelInstance = $generator->getModelInstance();
            
            // Cerca per slug, assumendo che tutti i model abbiano campo slug
            return $modelInstance->where('slug', $slug)->first();
            
        } catch (\Exception $e) {
            Log::error("Error finding record for resource {$generator->slug}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Invalida cache manualmente
     */
    public function invalidateCache(string $slug = null): void
    {
        if ($slug) {
            Cache::forget("resource_{$slug}");
        } else {
            // Invalida tutto
            Cache::forget('all_public_resources');
            
            // Invalida tutti i singoli cache (se necessario)
            $resources = ResourceGenerator::pluck('slug');
            foreach ($resources as $resourceSlug) {
                Cache::forget("resource_{$resourceSlug}");
            }
        }
    }

    /**
     * Verifica se uno slug è già in uso (per evitare conflitti)
     */
    public function isSlugAvailable(string $slug): bool
    {
        // Controlla nelle risorse generate
        if (ResourceGenerator::where('slug', $slug)->exists()) {
            return false;
        }

        // Controlla nelle route esistenti del sistema
        $reservedSlugs = [
            'admin', 'api', 'pages', 'news', 'login', 'register', 
            'password', 'home', 'dashboard', 'profile', 'settings'
        ];

        return !in_array($slug, $reservedSlugs);
    }

    /**
     * Ottieni statistiche sulle risorse (per debug/monitoring)
     */
    public function getStats(): array
    {
        return [
            'total_resources' => ResourceGenerator::count(),
            'public_resources' => ResourceGenerator::public()->count(),
            'cached_resources' => $this->getCachedCount(),
        ];
    }

    /**
     * Conta quante risorse sono in cache
     */
    private function getCachedCount(): int
    {
        $count = 0;
        $resources = ResourceGenerator::pluck('slug');
        
        foreach ($resources as $slug) {
            if (Cache::has("resource_{$slug}")) {
                $count++;
            }
        }
        
        return $count;
    }
}