<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ResourceGenerator extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'model_class',
        'has_categories',
        'is_public',
    ];

    protected $casts = [
        'has_categories' => 'boolean',
        'is_public' => 'boolean',
    ];

    /**
     * Boot del model con auto-invalidation cache
     */
    protected static function booted()
    {
        // Genera slug automaticamente se non presente
        static::creating(function ($resource) {
            if (empty($resource->slug) && !empty($resource->name)) {
                $resource->slug = Str::slug($resource->name);
            }
            
            if (empty($resource->model_class) && !empty($resource->name)) {
                $resource->model_class = 'App\\Models\\' . Str::studly($resource->name);
            }
        });

        static::updating(function ($resource) {
            // Invalida cache del vecchio slug
            if ($resource->isDirty('slug')) {
                Cache::forget('resource_' . $resource->getOriginal('slug'));
            }
        });

        static::saved(function ($resource) {
            // Invalida cache del slug corrente
            Cache::forget('resource_' . $resource->slug);
            Cache::forget('all_public_resources');
        });

        static::deleted(function ($resource) {
            // Invalida cache quando elimini
            Cache::forget('resource_' . $resource->slug);
            Cache::forget('all_public_resources');
        });
    }

    /**
     * Scope per risorse pubbliche
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Ottieni la classe del model
     */
    public function getModelInstance()
    {
        if (!class_exists($this->model_class)) {
            throw new \Exception("Model class {$this->model_class} not found for resource {$this->slug}");
        }

        return new $this->model_class();
    }
}
