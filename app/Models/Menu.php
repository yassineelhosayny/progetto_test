<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'slug',
    ];

    // Relazione: voci del menu
    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class)
                    ->whereNull('parent_id')
                    ->orderBy('sort_order');
    }

    public function allItems(): HasMany
    {
        return $this->hasMany(MenuItem::class)
                    ->orderBy('sort_order');
    }

    public function getMenuItems($menuName, $menuLocation): array
    {
        $menu = $this::with([
            'items' => function ($query) {
                $query->whereNull('parent_id')
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->with([
                        'children' => function ($subQuery) {
                            $subQuery->where('is_active', true)->orderBy('sort_order');
                        },
                        'page'
                    ]);
            }
        ])
        ->where('name', $menuName)
        ->orWhere('location', $menuLocation)
        ->where('is_active', true)
        ->first();

        $mainItems = array();
        $secondaryItems = array();
        $tertiaryItems = array();

        foreach ( $menu->items as $key =>$item ) {
            $mainItems[] = [
                'id' => $item->id,
                'label' => $item->label,
                'url' => $item->url,
                'children' => $item->children && $item->children->count() > 0 ? true : false,
                'level' => 0,
                'last_level' => false
            ];

            foreach ( $item->children as $index => $child ) {
                $secondaryItems[] = [
                    'parent_id' => $item->id,
                    'id' => $child->id,
                    'label' => $child->label,
                    'url' => $child->url,
                    'children' => $child->childrenItems && $child->childrenItems->count() > 0 ? true : false,
                    'level' => 1,
                    'last_level' => false
                ];
                if( $child->childrenItems && $child->childrenItems->count() > 0 ) {
                    foreach( $child->childrenItems as $subIndex => $subChild ) {
                        $tertiaryItems[] = [
                            'parent_id' => $child->id,
                            'id' => $subChild->id,
                            'label' => $subChild->label,
                            'url' => $subChild->url,
                            'children' => false,
                            'level' => 2,
                            'last_level' => true
                        ];
                    }
                }
            }
        }
        return ['main_items' => $mainItems, 'secondary_items' => $secondaryItems, 'tertiary_items' => $tertiaryItems];
    }
}
