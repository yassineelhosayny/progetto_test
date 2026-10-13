<?php

return '<?php

namespace App\\Models;

use Illuminate\\Database\\Eloquent\\Model;
use Spatie\\Translatable\\HasTranslations;

class {{MODEL_NAME}} extends Model
{
    use HasTranslations;

    protected $table = \'{{TABLE_NAME}}\';

    protected $fillable = [
        \'name\',
        \'slug\',
        \'description\',
        \'content\',
        \'blocks\',
        \'is_active\',
        \'sort_order\',
        \'meta_title\',
        \'meta_description\',
        \'meta_keywords\',
        \'template\',
        \'template_components\',
    ];

    protected $casts = [
        \'is_active\' => \'boolean\',
        \'blocks\' => \'array\',
        \'sort_order\' => \'integer\',
        \'template_components\' => \'array\',
    ];

    public $translatable = [
        \'name\',
        \'description\',
        \'content\',
        \'meta_title\',
        \'meta_description\',
        \'meta_keywords\',
    ];

    /**
     * Get the resource slug for this model
     */
    public static function getResourceSlug(): string
    {
        $resourceGenerator = \\App\\Models\\ResourceGenerator::where(\'model_class\', static::class)->first();
        return $resourceGenerator ? $resourceGenerator->slug : \Illuminate\Support\Str::slug(class_basename(static::class));
    }

    protected static function booted()
    {
        static::saving(function ($model) {
            // Genera slug automaticamente dal nome se non presente
            if (empty($model->slug) && !empty($model->name)) {
                $title = null;
                
                // Estrai il titolo dal campo translatable
                if (is_array($model->name)) {
                    $title = $model->name[\'it\'] ?? $model->name[\'en\'] ?? reset($model->name);
                } else {
                    $title = $model->name;
                }
                
                if ($title) {
                    $baseSlug = \Illuminate\Support\Str::slug($title);
                    $slug = $baseSlug;
                    $counter = 1;
                    
                    // Assicurati che lo slug sia unico
                    while (static::where(\'slug\', $slug)
                        ->when($model->exists, fn($q) => $q->where(\'id\', \'!=\', $model->id))
                        ->exists()) {
                        $slug = $baseSlug . \'-\' . $counter++;
                    }
                    
                    $model->slug = $slug;
                }
            }
        });
    }
}
';