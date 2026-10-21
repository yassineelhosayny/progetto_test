<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Video extends Model
{
    protected $fillable = [
        'video_subcategory_id',
        'name',
        'description',
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

    public function videoSubcategory(): BelongsTo
    {
        // La colonna si chiama video_subcategory_id (non video_sub_category_id).
        // La indichiamo esplicitamente per evitare che Laravel la deduca
        // diversamente in base a come viene scritto il nome della relazione.
        return $this->belongsTo(VideoSubcategory::class, 'video_subcategory_id');
    }

    public function sources(): HasMany
    {
        return $this->hasMany(VideoSource::class);
    }

    public function currentSource(): HasOne
    {
        return $this->hasOne(VideoSource::class)
            ->where('is_current', true);
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
