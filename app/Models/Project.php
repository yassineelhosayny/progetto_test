<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

class Project extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'featured_image',
        'gallery',
        'status',
        'published_at',
        'sort_order',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    public $translatable = [
        'title',
        'description',
        'content',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'gallery' => 'array',
        'published_at' => 'datetime',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saving(function (Project $project) {
            if (! empty($project->slug)) {
                return;
            }

            $title = static::getTitleForSlug($project);

            if (empty($title)) {
                return;
            }

            $base = Str::slug($title);
            $slug = $base ?: Str::slug(Str::limit($title, 50));
            $counter = 1;

            while (static::where('slug', $slug)
                ->when($project->exists, fn ($query) => $query->where('id', '!=', $project->id))
                ->exists()) {
                $slug = $base . '-' . $counter++;
            }

            $project->slug = $slug;
        });
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->where(function (Builder $query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', 'draft');
    }

    public function scopeScheduled(Builder $query): Builder
    {
        return $query->where('status', 'scheduled')
            ->where('published_at', '>', now());
    }

    public function isPublished(): bool
    {
        return $this->status === 'published'
            && ($this->published_at === null || $this->published_at <= now());
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isScheduled(): bool
    {
        return $this->status === 'scheduled'
            && $this->published_at !== null
            && $this->published_at > now();
    }

    public function getPublicUrlAttribute(): ?string
    {
        if (empty($this->slug)) {
            return null;
        }

        try {
            return route('projects.show', $this->slug);
        } catch (\Throwable $e) {
            return url('/projects/' . $this->slug);
        }
    }

    public function getCanonicalUrlAttribute(): ?string
    {
        return $this->public_url;
    }

    protected static function getTitleForSlug(Project $project): ?string
    {
        $title = null;

        if (method_exists($project, 'getTranslation')) {
            try {
                $title = $project->getTranslation('title', 'it', false);
            } catch (\Throwable $e) {
                $title = null;
            }
        }

        if (! empty($title)) {
            return $title;
        }

        $raw = $project->getAttributes()['title'] ?? null;

        if (is_string($raw) && $raw !== '') {
            $decoded = json_decode($raw, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded['it'] ?? reset($decoded) ?: null;
            }

            return $raw;
        }

        if (is_array($raw)) {
            return $raw['it'] ?? reset($raw) ?: null;
        }

        return null;
    }
}
