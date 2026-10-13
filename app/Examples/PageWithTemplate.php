<?php

namespace App\Examples;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;
use App\Models\FormTemplate;

/**
 * Esempio di come estendere il model Page per supportare i template dinamici
 */
class PageWithTemplate extends Model
{
    use HasTranslations;

    protected $table = 'pages'; // Usa la stessa tabella

    protected $fillable = [
        'title',
        'description',
        'slug',
        'blocks',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'status',
        'published_at',
        'is_homepage',
        'template',
        'template_components',
        'published',
        // Nuovi campi per i template dinamici
        'form_template_id',
        'template_data',
    ];

    public $translatable = [
        'title',
        'description',
        'blocks',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'template_components' => 'array',
        'template_data' => 'array', // Cast automatico per JSON
        'published_at' => 'datetime',
        'published' => 'boolean',
        'is_homepage' => 'boolean',
    ];

    /**
     * Relazione con FormTemplate
     */
    public function formTemplate(): BelongsTo
    {
        return $this->belongsTo(FormTemplate::class, 'form_template_id');
    }

    /**
     * Ottieni un valore dal template_data
     */
    public function getTemplateValue(string $key, $default = null)
    {
        return data_get($this->template_data, $key, $default);
    }

    /**
     * Setta un valore nel template_data
     */
    public function setTemplateValue(string $key, $value): void
    {
        $templateData = $this->template_data ?? [];
        data_set($templateData, $key, $value);
        $this->template_data = $templateData;
    }

    /**
     * Ottieni tutti i campi renderizzati del template
     */
    public function getRenderedTemplateFields(): array
    {
        if (!$this->formTemplate || !$this->template_data) {
            return [];
        }

        $rendered = [];
        $schema = $this->formTemplate->schema;

        foreach ($schema as $field) {
            $fieldName = $field['name'] ?? null;
            if ($fieldName && isset($this->template_data[$fieldName])) {
                $rendered[] = [
                    'label' => $field['label'] ?? $fieldName,
                    'name' => $fieldName,
                    'value' => $this->template_data[$fieldName],
                    'type' => $field['component'] ?? 'text',
                    'config' => $field,
                ];
            }
        }

        return $rendered;
    }

    /**
     * Verifica se ha un template dinamico
     */
    public function hasDynamicTemplate(): bool
    {
        return !is_null($this->form_template_id) && !is_null($this->formTemplate);
    }

    /**
     * Scope per pagine con template dinamici
     */
    public function scopeWithDynamicTemplate($query)
    {
        return $query->whereNotNull('form_template_id')
                    ->with('formTemplate');
    }

    /**
     * Scope per template specifico
     */
    public function scopeUsingTemplate($query, $templateId)
    {
        return $query->where('form_template_id', $templateId);
    }

    /**
     * Accessor per ottenere il nome del template
     */
    public function getTemplateLabelAttribute(): ?string
    {
        return $this->formTemplate ? $this->formTemplate->label : null;
    }

    /**
     * Boot del model
     */
    protected static function booted()
    {
        // Mantieni la logica esistente per lo slug
        static::saving(function ($page) {
            if (empty($page->slug)) {
                $title = null;
                if (method_exists($page, 'getTranslation')) {
                    try {
                        $title = $page->getTranslation('title', 'it', false);
                    } catch (\Throwable $e) {
                        $title = null;
                    }
                }

                if (!$title && is_array($page->title)) {
                    $title = $page->title['it'] ?? $page->title['en'] ?? array_values($page->title)[0] ?? null;
                }

                if (!$title) {
                    $title = $page->title;
                }

                if ($title) {
                    $page->slug = \Str::slug($title);
                }
            }

            // Valida i dati del template se presente
            if ($page->form_template_id && $page->isDirty('template_data')) {
                $page->validateTemplateData();
            }
        });
    }

    /**
     * Valida i dati del template
     */
    protected function validateTemplateData(): void
    {
        if (!$this->formTemplate) {
            return;
        }

        $schema = $this->formTemplate->schema;
        $templateData = $this->template_data ?? [];

        // Validazione basica - puoi espandere secondo le tue necessità
        foreach ($schema as $field) {
            $fieldName = $field['name'] ?? null;
            $required = $field['required'] ?? false;

            if ($required && $fieldName && empty($templateData[$fieldName])) {
                throw new \Exception("Field '{$fieldName}' is required by template '{$this->formTemplate->name}'");
            }
        }
    }

    /**
     * Esempio di utilizzo per ottenere dati formattati per la vista
     */
    public function getFormattedTemplateData(): array
    {
        $formatted = [];
        $renderedFields = $this->getRenderedTemplateFields();

        foreach ($renderedFields as $field) {
            $value = $field['value'];

            // Formatta il valore in base al tipo di campo
            switch ($field['type']) {
                case 'DatePicker':
                    $value = $value ? \Carbon\Carbon::parse($value)->format('d/m/Y') : null;
                    break;
                
                case 'Toggle':
                    $value = $value ? 'Sì' : 'No';
                    break;
                
                case 'FileUpload':
                    $value = $value ? (is_array($value) ? implode(', ', $value) : $value) : null;
                    break;
                
                case 'TagsInput':
                    $value = is_array($value) ? implode(', ', $value) : $value;
                    break;
            }

            $formatted[$field['name']] = [
                'label' => $field['label'],
                'value' => $value,
                'raw_value' => $field['value'],
                'type' => $field['type'],
            ];
        }

        return $formatted;
    }
}