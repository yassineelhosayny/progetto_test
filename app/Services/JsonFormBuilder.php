<?php

namespace App\Services;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TagsInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Grid;
use App\Models\FormTemplate;

class JsonFormBuilder
{
    /**
     * Create Filament form components from JSON configuration
     */
    public static function buildFromJson(array $jsonConfig): array
    {
        $components = [];

        foreach ($jsonConfig as $fieldConfig) {
            $component = self::createComponent($fieldConfig);
            if ($component) {
                $components[] = $component;
            }
        }

        return $components;
    }

    /**
     * Create a single component from configuration
     */
    private static function createComponent(array $config): mixed
    {
        $type = $config['type'] ?? 'text';
        $name = $config['name'] ?? '';
        
        if (empty($name)) {
            return null;
        }

        switch ($type) {
            case 'text':
                return TextInput::make($name)
                    ->label($config['label'] ?? ucfirst($name))
                    ->required($config['required'] ?? false)
                    ->maxLength($config['maxLength'] ?? null)
                    ->placeholder($config['placeholder'] ?? null)
                    ->helperText($config['helper'] ?? null);

            case 'textarea':
                return Textarea::make($name)
                    ->label($config['label'] ?? ucfirst($name))
                    ->required($config['required'] ?? false)
                    ->rows($config['rows'] ?? 3)
                    ->maxLength($config['maxLength'] ?? null)
                    ->placeholder($config['placeholder'] ?? null);

            case 'select':
                return Select::make($name)
                    ->label($config['label'] ?? ucfirst($name))
                    ->required($config['required'] ?? false)
                    ->options($config['options'] ?? [])
                    ->searchable($config['searchable'] ?? false)
                    ->multiple($config['multiple'] ?? false);

            case 'toggle':
                return Toggle::make($name)
                    ->label($config['label'] ?? ucfirst($name))
                    ->required($config['required'] ?? false)
                    ->default($config['default'] ?? false);

            case 'date':
                return DatePicker::make($name)
                    ->label($config['label'] ?? ucfirst($name))
                    ->required($config['required'] ?? false)
                    ->format($config['format'] ?? 'Y-m-d');

            case 'file':
                return FileUpload::make($name)
                    ->label($config['label'] ?? ucfirst($name))
                    ->required($config['required'] ?? false)
                    ->acceptedFileTypes($config['acceptedTypes'] ?? ['image/*'])
                    ->maxSize($config['maxSize'] ?? 2048);

            case 'rich_editor':
                return RichEditor::make($name)
                    ->label($config['label'] ?? ucfirst($name))
                    ->required($config['required'] ?? false)
                    ->helperText($config['helper'] ?? null)
                    ->columnSpanFull();

            case 'tags':
                return TagsInput::make($name)
                    ->label($config['label'] ?? ucfirst($name))
                    ->helperText($config['helper'] ?? null);

            case 'section':
                $sectionComponents = [];
                if (isset($config['fields'])) {
                    foreach ($config['fields'] as $fieldConfig) {
                        $component = self::createComponent($fieldConfig);
                        if ($component) {
                            $sectionComponents[] = $component;
                        }
                    }
                }
                return Section::make($config['label'] ?? 'Section')
                    ->schema($sectionComponents)
                    ->collapsible($config['collapsible'] ?? false);

            case 'tabs':
                $tabComponents = [];
                if (isset($config['tabs'])) {
                    foreach ($config['tabs'] as $tabConfig) {
                        $tabFields = [];
                        if (isset($tabConfig['fields'])) {
                            foreach ($tabConfig['fields'] as $fieldConfig) {
                                $component = self::createComponent($fieldConfig);
                                if ($component) {
                                    $tabFields[] = $component;
                                }
                            }
                        }
                        $tabComponents[] = Tabs\Tab::make($tabConfig['label'] ?? 'Tab')
                            ->schema($tabFields);
                    }
                }
                return Tabs::make($name)->tabs($tabComponents);

            case 'grid':
                $gridComponents = [];
                if (isset($config['fields'])) {
                    foreach ($config['fields'] as $fieldConfig) {
                        $component = self::createComponent($fieldConfig);
                        if ($component) {
                            $gridComponents[] = $component;
                        }
                    }
                }
                return Grid::make($config['columns'] ?? 2)
                    ->schema($gridComponents);

            default:
                return TextInput::make($name)
                    ->label($config['label'] ?? ucfirst($name));
        }
    }

    /**
     * Load form configuration from JSON file
     */
    public static function loadFromFile(string $filePath): array
    {
        if (!file_exists($filePath)) {
            throw new \Exception("JSON form file not found: {$filePath}");
        }

        $json = file_get_contents($filePath);
        $config = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Invalid JSON in form file: " . json_last_error_msg());
        }

        return self::buildFromJson($config);
    }

    /**
     * Load form configuration from database template
     */
    public static function loadFromTemplate(string $templateName): array
    {
        $template = FormTemplate::where('name', $templateName)
            ->where('is_active', true)
            ->first();

        if (!$template) {
            throw new \Exception("Template not found: {$templateName}");
        }

        if (!$template->validateSchema()) {
            throw new \Exception("Invalid template schema: {$templateName}");
        }

        return self::buildFromJson($template->schema['fields']);
    }

    /**
     * Load form configuration from database template ID
     */
    public static function loadFromTemplateId(int $templateId): array
    {
        $template = FormTemplate::where('id', $templateId)
            ->where('is_active', true)
            ->first();

        if (!$template) {
            throw new \Exception("Template not found with ID: {$templateId}");
        }

        if (!$template->validateSchema()) {
            throw new \Exception("Invalid template schema for ID: {$templateId}");
        }

        return self::buildFromJson($template->schema['fields']);
    }

    /**
     * Get available templates for select options
     */
    public static function getAvailableTemplates(): array
    {
        return FormTemplate::active()
            ->orderBy('category')
            ->orderBy('label')
            ->get()
            ->groupBy('category')
            ->map(function ($templates, $category) {
                return [
                    'label' => FormTemplate::getCategories()[$category] ?? ucfirst($category),
                    'templates' => $templates->pluck('label', 'id')->toArray(),
                ];
            })
            ->toArray();
    }

    /**
     * Get template options for select field
     */
    public static function getTemplateSelectOptions(): array
    {
        $options = [];
        $templates = self::getAvailableTemplates();

        foreach ($templates as $category => $data) {
            foreach ($data['templates'] as $id => $label) {
                $options[$id] = "{$data['label']} - {$label}";
            }
        }

        return $options;
    }

    /**
     * Preview template components without building them
     */
    public static function previewTemplate(string $templateName): array
    {
        $template = FormTemplate::where('name', $templateName)
            ->where('is_active', true)
            ->first();

        if (!$template) {
            return [];
        }

        return [
            'id' => $template->id,
            'name' => $template->name,
            'label' => $template->label,
            'description' => $template->description,
            'category' => $template->category,
            'version' => $template->version,
            'fields_count' => count($template->schema['fields'] ?? []),
            'schema' => $template->schema,
        ];
    }
}