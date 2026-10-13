<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Biostate\FilamentMenuBuilder\Models\MenuItem as BaseMenuItem;
use Biostate\FilamentMenuBuilder\Enums\MenuItemType;

class MenuItem extends BaseMenuItem
{
    use HasFactory;

    protected $fillable = [
        'id',
        'menuable_type',
        'menuable_id',
        'type_choice',
        'name',
        'type',
        'url',
        'route',
        'route_parameters',
        'target',
        'use_menuable_name',
        'link_class',
        'wrapper_class',
        'parameters',
        'menu_id',
        '_lft',
        '_rgt',
        'parent_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'route_parameters' => 'collection',
        'parameters' => 'collection',
        'type' => MenuItemType::class,
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($menuItem) {
            // Se è un child item e il menu_id non è settato, prendilo dal parent
            if ($menuItem->parent_id && !$menuItem->menu_id) {
                $parent = MenuItem::find($menuItem->parent_id);
                if ($parent) {
                    $menuItem->menu_id = $parent->menu_id;
                }
            }
        });

        // When an item is being deactivated, cascade the deactivation to all descendants
        static::updating(function ($menuItem) {
            if ($menuItem->isDirty('is_active')) {
                if ($menuItem->is_active === false) {
                    try {
                        // Deactivate all descendants when this node is deactivated
                        $menuItem->descendants()->update(['is_active' => false]);
                    } catch (\Throwable $e) {
                        \Log::error('Failed to cascade deactivate menu item descendants: ' . $e->getMessage());
                    }
                } elseif ($menuItem->is_active === true) {
                    try {
                        // When activating an item, ensure its ancestors are active
                        $menuItem->descendants()->update(['is_active' => true]);
                    } catch (\Throwable $e) {
                        \Log::error('Failed to cascade activate menu item ancestors: ' . $e->getMessage());
                    }
                }
            }
        });
    }

    // Relazione: menu di appartenenza
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    // Relazione: elemento padre
    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    // Relazione: elementi figli
    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')
                    ->where('is_active', true)
                    ->orderBy('sort_order');
    }

    // Relazione: pagina collegata
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    // Relazione: figli di secondo livello (MenuItemChild)
    public function childrenItems()
    {
        return $this->hasMany(MenuItemChild::class, 'menu_item_id');
    }

    // Genera l'URL finale
    public function getUrlAttribute($value)
    {
        // Debug temporaneo
        \Log::info("MenuItem #{$this->id}: page_id={$this->page_id}, value={$value}, has_page=" . ($this->page ? 'YES' : 'NO'));
        
        // Se c'è una pagina collegata
        if ($this->page_id && $this->page) {
            try {
                if ($this->page->slug !== 'homepage') {
                    $route = route('pages.show', $this->page->slug);
                } else {
                    $route = route('homepage');
                }
                // \Log::info("Generated route: {$route}");
                return $route;
            } catch (\Exception $e) {
                \Log::error("Error generating route: " . $e->getMessage());
                return '#';
            }
        }
        
        // Altrimenti usa l'URL personalizzato
        \Log::info("Using custom URL: " . ($value ?: '#'));
        return $value ?: '#';
    }

    // Override to safely resolve link attribute (wrap vendor logic)
    public function getLinkAttribute($value): string
    {
        try {
            return parent::getLinkAttribute($value);
        } catch (\Illuminate\Routing\Exceptions\UrlGenerationException $e) {
            \Log::warning("MenuItem #{$this->id} - UrlGenerationException: " . $e->getMessage());
            return '#';
        } catch (\Exception $e) {
            \Log::error("MenuItem #{$this->id} - Error generating link: " . $e->getMessage());
            return '#';
        }
    }
    // Scope: solo elementi attivi
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
