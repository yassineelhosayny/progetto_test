<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class NewsCategory extends Model
{
    use HasTranslations;
    /**
     * News associate a questa categoria
     */

    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'is_active',
    ];

    protected $translatable = [
        'name',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function news()
    {
        return $this->hasMany(News::class, 'news_category_id');
    }
}
