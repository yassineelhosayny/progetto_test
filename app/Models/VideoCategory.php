<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VideoCategory extends Model
{
    protected $fillable = [
        'video_demo_id',
        'name',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        // Inserisce automaticamente una nuova categoria in fondo alla propria demo.
        static::creating(function (VideoCategory $category): void {
            if ($category->sort_order === null) {
                $category->sort_order = ((int) static::query()
                    ->where('video_demo_id', $category->video_demo_id)
                    ->max('sort_order')) + 1;
            }
        });

    }

    public function videoDemo(): BelongsTo
    {
        return $this->belongsTo(VideoDemo::class);
    }

    public function subcategories(): HasMany
    {
        return $this->hasMany(VideoSubcategory::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order');
    }
}
