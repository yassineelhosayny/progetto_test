<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoSource extends Model
{
    protected $fillable = [
        'video_id',
        'provider',
        'label',
        'external_id',
        'provider_library_id',
        'source_url',
        'thumbnail_url',
        'upload_method',
        'upload_status',
        'upload_error',
        'version_date',
        'is_current',
        'is_active',
        'sort_order',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'version_date' => 'datetime',
            'is_current' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'metadata' => 'array',
        ];
    }

    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    /**
     * Link riproducibile calcolato dai dati della sorgente.
     *
     * Non viene salvato in una nuova colonna: Vimeo e YouTube usano
     * l'external_id, mentre Bunny conserva già il proprio URL completo.
     */
    public function getPlaybackUrlAttribute(): ?string
    {
        if ($this->provider === 'bunny') {
            return filled($this->source_url) ? $this->source_url : null;
        }

        if (blank($this->external_id)) {
            return null;
        }

        return match ($this->provider) {
            'vimeo' => $this->makeVimeoPlaybackUrl(),
            'youtube' => 'https://www.youtube.com/watch?v=' . rawurlencode($this->external_id),
            default => null,
        };
    }

    private function makeVimeoPlaybackUrl(): string
    {
        $url = 'https://player.vimeo.com/video/' . rawurlencode($this->external_id);
        $unlistedCode = $this->metadata['unlisted_code'] ?? null;

        if (filled($unlistedCode)) {
            $url .= '?h=' . rawurlencode($unlistedCode);
        }

        return $url;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeCurrent(Builder $query): Builder
    {
        return $query->where('is_current', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order');
    }
}
