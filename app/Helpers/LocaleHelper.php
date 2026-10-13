<?php

namespace App\Helpers;

use App\Forms\Components\TinyMceEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Builder;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Illuminate\Support\Str;
use App\Services\TemplateManager;
use Filament\Forms\Components\MarkdownEditor;
use AmidEsfahani\FilamentTinyEditor\TinyEditor;

class LocaleHelper
{
    /**
     * Get all supported locales from config
     */
    public static function getSupportedLocales(): array
    {
        return config('locales.supported', []);
    }

    /**
     * Get default locale
     */
    public static function getDefaultLocale(): string
    {
        return config('locales.default', 'it');
    }

    /**
     * Get locales sorted by their sort order
     */
    public static function getSortedLocales(): array
    {
        $locales = static::getSupportedLocales();

        uasort($locales, function ($a, $b) {
            return ($a['sort'] ?? 999) <=> ($b['sort'] ?? 999);
        });

        return $locales;
    }

    /**
     * Generate dynamic language tabs for Filament forms
     * 
     * @param array $fieldGroups - Array of field groups with their fields
     * @param array $options - Additional options for customization
     * @return array
     */
    public static function generateLanguageTabs(array $fieldGroups, array $options = []): array
    {
        $locales = static::getSortedLocales();
        $tabs = [];

        foreach ($locales as $locale => $config) {
            $tabs[] = Tabs\Tab::make($config['flag'] . ' ' . $config['native'])
                ->schema(static::generateTabSchema($locale, $fieldGroups, $options));
        }

        return $tabs;
    }

    /**
     * Generate schema for a specific language tab
     */
    protected static function generateTabSchema(string $locale, array $fieldGroups, array $options = []): array
    {
        $schema = [];
        $localeConfig = config("locales.supported.{$locale}");
        $isRequired = $localeConfig['required'] ?? false;

        foreach ($fieldGroups as $groupName => $fields) {
            $sectionSchema = [];

            foreach ($fields as $fieldName => $fieldConfig) {
                if (is_numeric($fieldName)) {
                    // Se è un array semplice ['title', 'description']
                    $fieldName = $fieldConfig;
                    $fieldConfig = [];
                }

                $component = static::createFieldComponent($fieldName, $locale, $fieldConfig, $isRequired);
                if ($component) {
                    $sectionSchema[] = $component;
                }
            }

            if (!empty($sectionSchema)) {
                $label = $fields['label'] ?? static::formatSectionTitle($groupName, $locale);

                $schema[] = Section::make($label)
                    ->schema($sectionSchema);
            }
        }

        return $schema;
    }

    /**
     * Create appropriate Filament component for a field
     */
    protected static function createFieldComponent(string $fieldName, string $locale, array $config, bool $isRequired): mixed
    {
        $fieldKey = "{$fieldName}.{$locale}";
        $label = static::formatFieldLabel($fieldName, $locale);
        $required = $isRequired && ($config['required'] ?? false);

        switch ($fieldName) {
            case 'title':
            case 'name':
                $component = TextInput::make($fieldKey)
                    ->label($label)
                    ->required($required)
                    ->maxLength($config['maxLength'] ?? 255);

                // Se è configurato come live, aggiungi reattività
                if (isset($config['live']) && $config['live']) {
                    $component = $component
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, $set, $get) use ($locale) {
                            // Auto-genera slug solo per la lingua di default (it) o se è la prima lingua
                            if (($locale === 'it' || !$get('slug')) && !empty($state)) {
                                $currentSlug = $get('slug');
                                if (empty($currentSlug)) {
                                    $slug = Str::slug($state);
                                    $set('slug', $slug);
                                }
                            }
                        });
                }

                return $component;

            // case 'description':
            //     return MarkdownEditor::make($fieldKey)
            //         ->label($label);

            case 'description':

                return TinyEditor::make($fieldKey)
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsVisibility('public')
                    ->fileAttachmentsDirectory('uploads')
                    ->profile('default|simple|full|minimal|none|custom')
                    ->resize('both')
                    ->columnSpan('full')
                    ->label($label);

            case 'excerpt':
                return Textarea::make($fieldKey)
                    ->label($label)
                    ->rows($config['rows'] ?? 3)
                    ->maxLength($config['maxLength'] ?? 300);
            // Rimosso helperText per debug

            case 'content':
                return RichEditor::make($fieldKey)
                    ->label($label)
                    ->columnSpanFull();
            // Rimosso helperText per debug

            case 'blocks':
                // Se i blocchi sono esplicitamente vuoti e non template-dependent, ritorna un campo nascosto
                if (!isset($config['template_dependent']) || !$config['template_dependent']) {
                    if (empty($config['blocks'])) {
                        return TextInput::make($fieldKey)
                            ->label($label)
                            ->default('[]')
                            ->hidden()
                            ->helperText('No blocks available for this template');
                    }
                }

                return Builder::make($fieldKey)
                    ->label($label)
                    ->blocks(function ($get) use ($config) {
                        try {
                            if (isset($config['template_dependent']) && $config['template_dependent']) {
                                $template = $get('template') ?? 'default';
                                return TemplateManager::getBlocksForTemplate($template);
                            }
                            return $config['blocks'] ?? [];
                        } catch (\Exception $e) {
                            \Log::error('Error loading blocks: ' . $e->getMessage());
                            // In caso di errore, ritorna array vuoto
                            return [];
                        }
                    })
                    ->visible(function ($get) use ($config) {
                        // Mostra il campo blocks solo se il template supporta blocchi configurabili
                        if (isset($config['template_dependent']) && $config['template_dependent']) {
                            $template = $get('template') ?? 'default';
                            return TemplateManager::hasConfigurableBlocks($template);
                        }
                        return !empty($config['blocks']); // Mostra solo se ci sono blocchi definiti
                    })
                    ->collapsed()
                    ->collapsible()
                    ->cloneable()
                    ->addActionLabel($config['addLabel'] ?? static::getDefaultAddLabel($locale));

            case 'meta_title':
                return TextInput::make($fieldKey)
                    ->label($label);
                    // ->maxLength($config['maxLength'] ?? 60)
                    // ->helperText($config['helperText'] ?? static::getDefaultHelperText($fieldName, $locale));

            case 'meta_description':
                return Textarea::make($fieldKey)
                    ->label($label)
                    ->rows($config['rows'] ?? 3);
                    // ->maxLength($config['maxLength'] ?? 160)
                    // ->helperText($config['helperText'] ?? static::getDefaultHelperText($fieldName, $locale));

            case 'meta_keywords':
                return TextInput::make($fieldKey)
                    ->label($label);
                    // ->helperText($config['helperText'] ?? static::getDefaultHelperText($fieldName, $locale))
                    // ->placeholder($config['placeholder'] ?? static::getDefaultPlaceholder($fieldName, $locale));

            default:
                return null;
        }
    }

    /**
     * Get slug updater function for the default locale
     */
    protected static function getSlugUpdater(string $locale): ?\Closure
    {
        if ($locale !== static::getDefaultLocale()) {
            return null;
        }

        return function (string $operation, $state, $set, $get) {
            if ($operation !== 'create') {
                return;
            }
            // Auto-generate slug only if empty
            if (empty($get('slug'))) {
                $set('slug', Str::slug($state));
            }
        };
    }

    /**
     * Format field label based on locale
     */
    protected static function formatFieldLabel(string $fieldName, string $locale): string
    {
        $labels = [
            'it' => [
                'title' => 'Titolo',
                'description' => 'Descrizione',
                'excerpt' => 'Riassunto',
                'content' => 'Contenuto',
                'blocks' => 'Blocchi contenuto',
                'meta_title' => 'Meta Titolo',
                'meta_description' => 'Meta Descrizione',
                'meta_keywords' => 'Meta Keywords',
            ],
            'en' => [
                'title' => 'Title',
                'description' => 'Description',
                'excerpt' => 'Excerpt',
                'content' => 'Content',
                'blocks' => 'Content Blocks',
                'meta_title' => 'Meta Title',
                'meta_description' => 'Meta Description',
                'meta_keywords' => 'Meta Keywords',
            ],
        ];

        return $labels[$locale][$fieldName] ?? ucfirst(str_replace('_', ' ', $fieldName));
    }

    /**
     * Format section title based on locale
     */
    protected static function formatSectionTitle(string $sectionName, string $locale): string
    {
        $titles = [
            'it' => [
                'basic' => 'Informazioni Base',
                'content' => 'Contenuto',
                'seo' => 'SEO & Meta',
                'blocks' => 'Blocchi Contenuto',
            ],
            'en' => [
                'basic' => 'Basic Information',
                'content' => 'Content',
                'seo' => 'SEO & Meta',
                'blocks' => 'Content Blocks',
            ],
        ];

        return $titles[$locale][$sectionName] ?? ucfirst(str_replace('_', ' ', $sectionName));
    }

    /**
     * Get default helper text based on field and locale
     */
    protected static function getDefaultHelperText(string $fieldName, string $locale): string
    {
        $texts = [
            'it' => [
                'description' => 'Breve descrizione per risultati di ricerca e condivisioni social',
                'excerpt' => 'Breve riassunto per le liste di notizie',
                'content' => 'Contenuto principale dell\'articolo',
                'meta_title' => 'Ottimale: 50-60 caratteri. Lascia vuoto per usare il titolo.',
                'meta_description' => 'Ottimale: 140-160 caratteri. Lascia vuoto per usare la descrizione.',
                'meta_keywords' => 'Parole chiave separate da virgola. Generalmente non utilizzate dai motori di ricerca moderni.',
            ],
            'en' => [
                'description' => 'Brief description for search results and social sharing',
                'excerpt' => 'Brief summary for news listings',
                'content' => 'Main article content',
                'meta_title' => 'Optimal: 50-60 characters. Leave empty to use title.',
                'meta_description' => 'Optimal: 140-160 characters. Leave empty to use description.',
                'meta_keywords' => 'Comma-separated keywords. Generally not used by modern search engines.',
            ],
        ];

        return $texts[$locale][$fieldName] ?? '';
    }

    /**
     * Get default placeholder based on field and locale
     */
    protected static function getDefaultPlaceholder(string $fieldName, string $locale): string
    {
        $placeholders = [
            'it' => [
                'meta_keywords' => 'parola1, parola2, parola3',
            ],
            'en' => [
                'meta_keywords' => 'keyword1, keyword2, keyword3',
            ],
        ];

        return $placeholders[$locale][$fieldName] ?? '';
    }

    /**
     * Get default add label based on locale
     */
    protected static function getDefaultAddLabel(string $locale): string
    {
        $labels = [
            'it' => 'Aggiungi blocco contenuto',
            'en' => 'Add Content Block',
        ];

        return $labels[$locale] ?? 'Add Block';
    }
}