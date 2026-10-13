<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Biostate\FilamentMenuBuilder\Traits\Menuable;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;
use App\Services\TemplateManager;
use Spatie\Permission\Models\Permission;
use App\Contracts\FrontendSearchable;
use App\Traits\UsesFrontendSearch;
use Illuminate\Database\Eloquent\Builder;

class Page extends Model implements FrontendSearchable
{
    use HasTranslations;
    use Menuable;
    use UsesFrontendSearch;

    protected $fillable = [
        'title',
        'description',
        'slug',
        'blocks',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'status',
        'published_at',
        'is_homepage',
        'template',
        'template_components',
        'published', // manteniamo per backward compatibility
    ];

    public $translatable = [
        'title',
        'description',
        'blocks',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'template_components' => 'array',
        'published_at' => 'datetime',
        'published' => 'boolean',
        'is_homepage' => 'boolean',
    ];

    public static $searchable = true;

    protected static function booted()
    {
        static::saving(function (Page $page) {
            // se slug già presente non toccare
            if (! empty($page->slug)) {
                return;
            }

            // 1) prova getTranslation('title','it') se disponibile
            $title = null;
            if (method_exists($page, 'getTranslation')) {
                try {
                    $title = $page->getTranslation('title', 'it', false);
                } catch (\Throwable $e) {
                    $title = null;
                }
            }

            // 2) fallback: leggi raw attribute (JSON string o array)
            if (empty($title)) {
                $raw = $page->getAttributes()['title'] ?? null;

                if (is_string($raw) && $raw !== '') {
                    $decoded = json_decode($raw, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $title = $decoded['it'] ?? reset($decoded) ?? null;
                    } else {
                        $title = $raw;
                    }
                } elseif (is_array($raw)) {
                    $title = $raw['it'] ?? reset($raw) ?? null;
                }
            }

            if (empty($title)) {
                return;
            }

            // genera slug di base e rendilo unico
            $base = Str::slug($title);
            $slug = $base ?: Str::slug(Str::limit($title, 50));
            $counter = 1;

            while (static::where('slug', $slug)
                ->when($page->exists, fn($q) => $q->where('id', '!=', $page->id))
                ->exists()) {
                $slug = $base . '-' . $counter++;
            }

            $page->slug = $slug;
        });

        // Garantisce che ci sia solo una homepage
        static::saving(function (Page $page) {
            if ($page->is_homepage) {
                // Rimuove il flag homepage da tutte le altre pagine
                static::where('id', '!=', $page->id)->update(['is_homepage' => false]);
            }
        });
        // Crea permesso granulare dopo il salvataggio
        static::saved(function (Page $page) {
            $viewPerm = 'view page ' . $page->id;
            $updatePerm = 'update page ' . $page->id;
            foreach ([$viewPerm, $updatePerm] as $permName) {
                if (!Permission::where('name', $permName)->where('guard_name', 'web')->exists()) {
                    Permission::create([
                        'name' => $permName,
                        'guard_name' => 'web',
                    ]);
                }
            }
        });
    }

    public function getPublicUrlAttribute(): ?string
    {
        if (empty($this->slug)) {
            return null;
        }

        // Se è la homepage, ritorna la root URL
        if ($this->is_homepage) {
            return url('/');
        }

        try {
            return route('pages.show', $this->slug);
        } catch (\Throwable $e) {
            // se la route non esiste o siamo in CLI senza contesto, fallback a URL assoluto
            return url('/' . $this->slug);
        }
    }

    // Provide the menu link expected by the Menuable trait
    public function getMenuLinkAttribute(): string
    {
        return $this->getPublicUrlAttribute() ?? url('/' . ($this->slug ?? ''));
    }

    // Use the translated title as the label for Filament search selects
    public static function getFilamentSearchLabel(): string
    {
        return 'title';
    }

    // =================== STATUS MANAGEMENT ===================
    
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where(function($q) {
                        $q->whereNull('published_at')
                          ->orWhere('published_at', '<=', now());
                    });
    }
    
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }
    
    public function scopeScheduled($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '>', now());
    }
    
    public function isPublished(): bool
    {
        return $this->status === 'published' && 
               ($this->published_at === null || $this->published_at <= now());
    }
    
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }
    
    public function isScheduled(): bool
    {
        return $this->status === 'published' && 
               $this->published_at && $this->published_at > now();
    }

    // =================== SEO HELPERS ===================
    
    public function getMetaAttribute($value, $field)
    {
        $locale = app()->getLocale();

        // Legge il valore raw dall'attributo per evitare ricorsione con getTranslation()
        $raw = $this->getAttributes()["meta_{$field}"] ?? null;

        if (is_string($raw) && $raw !== '') {
            $decoded = json_decode($raw, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded[$locale] ?? reset($decoded);
            }

            return $raw;
        } elseif (is_array($raw)) {
            return $raw[$locale] ?? reset($raw);
        } elseif ($value) {
            return $value;
        }

        // Fallback al titolo normale
        try {
            return $this->getTranslation('title', $locale);
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function getCanonicalUrlAttribute()
    {
        return url('/' . $this->slug);
    }

    /**
     * Get available templates (delegate to TemplateManager)
     */
    public static function getAvailableTemplates(): array
    {
        return TemplateManager::getAvailableTemplates();
    }

    /**
     * Get template configuration (delegate to TemplateManager)
     */
    public function getTemplateConfig(): array
    {
        return TemplateManager::getTemplateConfig($this->template ?? 'default');
    }

    /**
     * Get available component types for current template
     */
    public function getAvailableComponentTypes(): array
    {
        return TemplateManager::getAvailableComponentTypes($this->template ?? 'default');
    }

    /**
     * Check if template has specific component type
     */
    public function hasComponentType(string $componentType): bool
    {
        return TemplateManager::hasComponentType($this->template ?? 'default', $componentType);
    }

    public function scopeFrontendSearchVisible(Builder $query): void
    {
        $query->where('status', 'published');
    }

    public function getFrontendSearchType(): string
    {
        return 'page';
    }

    public function getFrontendSearchUrl(): string
    {
        return route('pages.show', $this->slug);
    }
}