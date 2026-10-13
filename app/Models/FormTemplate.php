<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class FormTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'label',
        'description',
        'category',
        'schema',
        'metadata',
        'is_active',
        'is_system',
        'version',
        'sort_order',
    ];

    protected $casts = [
        'schema' => 'array',
        'metadata' => 'array',
        'is_active' => 'boolean',
        'is_system' => 'boolean',
    ];

    /**
     * Get templates by category
     */
    public function scopeByCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }

    /**
     * Get only active templates
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Get only custom (non-system) templates
     */
    public function scopeCustom(Builder $query): Builder
    {
        return $query->where('is_system', false);
    }

    /**
     * Get available categories
     */
    public static function getCategories(): array
    {
        return [
            'page' => 'Page Templates',
            'news' => 'News Templates',
            'form' => 'Form Templates',
            'modal' => 'Modal Templates',
            'widget' => 'Widget Templates',
            'custom' => 'Custom Templates',
        ];
    }

    /**
     * Validate JSON schema structure
     */
    public function validateSchema(): bool
    {
        if (!is_array($this->schema)) {
            return false;
        }

        // Basic validation - should contain 'fields' array
        return isset($this->schema['fields']) && is_array($this->schema['fields']);
    }

    /**
     * Get form components from schema using JsonFormBuilder
     */
    public function getFormComponents(): array
    {
        if (!$this->validateSchema()) {
            return [];
        }

        return \App\Services\JsonFormBuilder::buildFromJson($this->schema['fields']);
    }

    /**
     * Export template as JSON
     */
    public function export(): array
    {
        return [
            'name' => $this->name,
            'label' => $this->label,
            'description' => $this->description,
            'category' => $this->category,
            'schema' => $this->schema,
            'metadata' => $this->metadata,
            'version' => $this->version,
            'exported_at' => now()->toISOString(),
        ];
    }

    /**
     * Import template from JSON
     */
    public static function import(array $data): self
    {
        return static::create([
            'name' => $data['name'],
            'label' => $data['label'],
            'description' => $data['description'] ?? null,
            'category' => $data['category'] ?? 'custom',
            'schema' => $data['schema'],
            'metadata' => array_merge($data['metadata'] ?? [], [
                'imported_at' => now()->toISOString(),
                'original_version' => $data['version'] ?? '1.0.0',
            ]),
            'version' => '1.0.0', // Reset version on import
            'is_system' => false, // Imported templates are never system
        ]);
    }
}
