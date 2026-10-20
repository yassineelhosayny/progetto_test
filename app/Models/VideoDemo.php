<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class VideoDemo extends Model
{
    protected $fillable = [
        'client_id',
        'title',
        'slug',
        'logo',
        'is_active',
        'requires_password',
        'password',
        'sort_order',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'requires_password' => 'boolean',
            'password' => 'hashed',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        // Le nuove demo vengono inserite automaticamente dopo quelle esistenti.
        // Un valore esplicito, per esempio proveniente da un seeder, viene rispettato.
        static::creating(function (VideoDemo $videoDemo): void {
            if ($videoDemo->sort_order === null) {
                $videoDemo->sort_order = ((int) static::query()->max('sort_order')) + 1;
            }
        });

        static::saving(function (VideoDemo $videoDemo): void {
            if (! $videoDemo->requires_password) {
                $videoDemo->password = null;
            }

            if ( ! empty($videoDemo->title)) {
                $slugSource = ! empty($videoDemo->slug) ? $videoDemo->slug : $videoDemo->title;
                $videoDemo->slug = static::makeUniqueSlug($slugSource, $videoDemo->getKey());
            }
        });
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(VideoCategory::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order');
    }

    private static function makeUniqueSlug(string $value, int|string|null $ignoreId = null): string
    {
        $baseSlug = Str::slug($value) ?: 'video-demo';
        $slug = $baseSlug;
        $suffix = 2;

        while (static::query()
            ->where('slug', $slug)
            ->when($ignoreId !== null, fn (Builder $query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $baseSlug . '-' . $suffix++;
        }

        return $slug;
    }
}
