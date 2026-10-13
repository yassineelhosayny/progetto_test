<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class News extends Model
{
    use HasTranslations;

    protected $table = 'news';

    protected $fillable = [
        'blocks',
        'content',
        'description',
        'excerpt',
        'excerpt_image',
        'featured',
        'featured_image',
        'meta_description',
        'meta_keywords',
        'meta_title',
        'news_category_id',
        'publication_date',
        'published',
        'published_at',
        'slug',
        'status',
        'tags',
        'title',
    ];


    public $translatable = [
        'title',
        'description',
        'excerpt',
        'content',
        'blocks',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'blocks' => 'array',
        'tags' => 'array',
        'published_at' => 'datetime',
        'published' => 'boolean',
        'featured' => 'boolean',
        'publication_date' => 'date',
    ];

    public static $searchable = true;

    protected static function booted()
    {
        static::saving(function (News $news) {
            // Auto-genera slug se vuoto
            if (!empty($news->slug)) {
                return;
            }

            // Prova getTranslation('title','it') se disponibile
            $title = null;
            if (method_exists($news, 'getTranslation')) {
                try {
                    $title = $news->getTranslation('title', 'it', false);
                } catch (\Throwable $e) {
                    $title = null;
                }
            }

            // Fallback: leggi raw attribute (JSON string o array)
            if (empty($title)) {
                $raw = $news->getAttributes()['title'] ?? null;

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

            // Genera slug di base e rendilo unico
            $base = Str::slug($title);
            $slug = $base ?: Str::slug(Str::limit($title, 50));
            $counter = 1;

            while (
                static::where('slug', $slug)
                    ->when($news->exists, fn($q) => $q->where('id', '!=', $news->id))
                    ->exists()
            ) {
                $slug = $base . '-' . $counter++;
            }

            $news->slug = $slug;
        });

        // Crea permesso granulare dopo il salvataggio
        static::saved(function (News $news) {
            $viewPerm = 'view news ' . $news->id;
            $updatePerm = 'update news ' . $news->id;
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

    /**
     * Categoria associata alla news
     */
    public function category()
    {
        return $this->belongsTo(NewsCategory::class, 'news_category_id');
    }

    public function getPublicUrlAttribute(): ?string
    {
        if (empty($this->slug)) {
            return null;
        }

        try {
            return route('news.show', $this->slug);
        } catch (\Throwable $e) {
            // Fallback a URL assoluto
            return url('/news/' . $this->slug);
        }
    }

    // =================== STATUS MANAGEMENT ===================

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where(function ($q) {
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
        return $query->where('status', 'scheduled')
            ->where('published_at', '>', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }


    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('news_category_id', $categoryId);
    }

    public function isPublished(): bool
    {
        return $this->status === 'published' &&
            ($this->published_at === null || $this->published_at->isPast());
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isScheduled(): bool
    {
        return $this->status === 'scheduled' &&
            $this->published_at !== null &&
            $this->published_at->isFuture();
    }

    // =================== SEO HELPERS ===================

    public function getMetaTitleAttribute($value)
    {
        $locale = app()->getLocale();

        // Se c'è un meta_title tradotto per la locale corrente, usalo
        if (!empty($value)) {
            return $value;
        }

        // Fallback: usa il titolo tradotto
        $title = $this->getTranslation('title', $locale, false);
        if (!empty($title)) {
            return $title;
        }

        // Ulteriore fallback: titolo con fallback di Spatie
        return $this->getTranslation('title', $locale, true);
    }


    public function getMetaDescriptionAttribute($value)
    {
        $locale = app()->getLocale();

        if (!empty($value)) {
            return Str::limit($value, 160);
        }

        // Fallback: excerpt tradotto
        $excerpt = $this->getTranslation('excerpt', $locale, false);
        if (!empty($excerpt)) {
            return Str::limit($excerpt, 160);
        }

        // Fallback: description tradotta
        $description = $this->getTranslation('description', $locale, false);
        if (!empty($description)) {
            return Str::limit($description, 160);
        }

        // Ultimo fallback: description con fallback locale di Spatie
        return Str::limit($this->getTranslation('description', $locale, true), 160);
    }


    public function getMetaKeywordsAttribute($value)
    {
        return $value;
    }

    public function getCanonicalUrlAttribute()
    {
        return $this->public_url;
    }

    // =================== NEWS SPECIFIC HELPERS ===================

    public function getExcerptOrDescriptionAttribute()
    {
        $excerpt = $this->getTranslation('excerpt', app()->getLocale(), false);
        if ($excerpt) {
            return $excerpt;
        }

        return Str::limit($this->getTranslation('description', app()->getLocale()), 200);
    }

    public function getFormattedPublishedDateAttribute()
    {
        return $this->published_at ? $this->published_at->format('d/m/Y') : null;
    }

    public function getTagsArrayAttribute()
    {
        return is_array($this->tags) ? $this->tags : [];
    }

    // =================== STATIC METHODS FOR NEWS GALLERY ===================

    /**
     * Get news for gallery display with pagination
     */
    public static function getForGallery(int $perPage = 12, ?string $category = null)
    {
        $query = static::published()
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc');

        if ($category) {
            $query->byCategory($category);
        }

        return $query->paginate($perPage);
    }

    /**
     * Get featured news for highlights
     */
    public static function getFeatured(int $limit = 6)
    {
        return static::published()
            ->featured()
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get all available categories
     */

    public static function getAvailableCategories()
    {
        // Restituisce tutte le categorie usate da news pubblicate
        $ids = static::published()
            ->whereNotNull('news_category_id')
            ->distinct('news_category_id')
            ->pluck('news_category_id')
            ->filter()
            ->sort()
            ->values();
        return \App\Models\NewsCategory::whereIn('id', $ids)->pluck('name', 'id');
    }

    /**
     * Get recent news
     */
    public static function getRecent(int $limit = 10)
    {
        return static::published()
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();
    }
}