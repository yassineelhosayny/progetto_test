<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Client extends Model
{
    protected $fillable = [
        'client_name',
        'slug',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Client $client): void {
            if (empty($client->client_name)) {
                return;
            }

            // Se lo slug è stato scritto manualmente, lo normalizza (es. "Agras Demo"
            // diventa "agras-demo"). Se è vuoto, lo genera dal nome del cliente.
            $slugSource = ! empty($client->slug) ? $client->slug : $client->client_name;

            $client->slug = static::makeUniqueSlug($slugSource, $client->getKey());
        });
    }

    public function videoDemos(): HasMany
    {
        return $this->hasMany(VideoDemo::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    private static function makeUniqueSlug(string $value, int|string|null $ignoreId = null): string
    {
        $baseSlug = Str::slug($value) ?: 'client';
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
