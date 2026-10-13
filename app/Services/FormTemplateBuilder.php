<?php

namespace App\Services;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Grid;

class FormTemplateBuilder
{
    /**
     * Get available component types for the template builder
     */
    public static function getAvailableComponents(): array
    {
        return [
            'layout' => [
                'label' => 'Layout Components',
                'components' => [
                    'section' => [
                        'label' => 'Section',
                        'icon' => 'heroicon-o-rectangle-group',
                        'description' => 'Group fields in a collapsible section',
                        'config' => static::getSectionConfig(),
                    ],
                    'grid' => [
                        'label' => 'Grid',
                        'icon' => 'heroicon-o-squares-2x2',
                        'description' => 'Arrange fields in columns',
                        'config' => static::getGridConfig(),
                    ],
                    'tabs' => [
                        'label' => 'Tabs',
                        'icon' => 'heroicon-o-folder',
                        'description' => 'Organize content in tabs',
                        'config' => static::getTabsConfig(),
                    ],
                ],
            ],
            'input' => [
                'label' => 'Input Fields',
                'components' => [
                    'text' => [
                        'label' => 'Text Input',
                        'icon' => 'heroicon-o-pencil',
                        'description' => 'Single line text input',
                        'config' => static::getTextInputConfig(),
                    ],
                    'textarea' => [
                        'label' => 'Textarea',
                        'icon' => 'heroicon-o-document-text',
                        'description' => 'Multi-line text input',
                        'config' => static::getTextareaConfig(),
                    ],
                    'rich_editor' => [
                        'label' => 'Rich Editor',
                        'icon' => 'heroicon-o-pencil-square',
                        'description' => 'WYSIWYG text editor',
                        'config' => static::getRichEditorConfig(),
                    ],
                    'select' => [
                        'label' => 'Select',
                        'icon' => 'heroicon-o-chevron-down',
                        'description' => 'Dropdown selection',
                        'config' => static::getSelectConfig(),
                    ],
                    'toggle' => [
                        'label' => 'Toggle',
                        'icon' => 'heroicon-o-power',
                        'description' => 'Boolean toggle switch',
                        'config' => static::getToggleConfig(),
                    ],
                    'date' => [
                        'label' => 'Date Picker',
                        'icon' => 'heroicon-o-calendar',
                        'description' => 'Date selection field',
                        'config' => static::getDateConfig(),
                    ],
                    'file' => [
                        'label' => 'File Upload',
                        'icon' => 'heroicon-o-arrow-up-tray',
                        'description' => 'File upload field',
                        'config' => static::getFileConfig(),
                    ],
                    'tags' => [
                        'label' => 'Tags Input',
                        'icon' => 'heroicon-o-hashtag',
                        'description' => 'Multiple tags input',
                        'config' => static::getTagsConfig(),
                    ],
                ],
            ],
        ];
    }

    /**
     * Get the template builder form schema
     */
    public static function getBuilderSchema(): array
    {
        return [
            Builder::make('template_schema')
                ->label('Form Template Builder')
                ->blocks(static::getBuilderBlocks())
                ->blockNumbers(false)
                ->collapsible()
                ->cloneable()
                ->addActionLabel('Add Component')
                ->live(debounce: 1000)
                ->afterStateUpdated(function ($state, $set) {
                    // Sincronizza automaticamente lo schema quando il builder cambia
                    if (!empty($state)) {
                        $schema = static::convertToJsonSchema($state);
                        $set('schema', $schema);
                        $set('schema_json', json_encode($schema, JSON_PRETTY_PRINT));
                    }
                })
                ->columnSpanFull(),
        ];
    }

    /**
     * Get builder blocks for each component type
     */
    public static function getBuilderBlocks(): array
    {
        $blocks = [];
        $components = static::getAvailableComponents();

        foreach ($components as $categoryKey => $category) {
            foreach ($category['components'] as $componentKey => $component) {
                $blocks[] = Builder\Block::make($componentKey)
                    ->label($component['label'])
                    ->icon($component['icon'])
                    ->schema($component['config']);
            }
        }

        return $blocks;
    }

    // Component Configuration Methods

    protected static function getSectionConfig(): array
    {
        return [
            TextInput::make('label')
                ->label('Section Label')
                ->required()
                ->placeholder('Section Title'),
            
            Toggle::make('collapsible')
                ->label('Collapsible')
                ->helperText('Make this section collapsible'),
            
            Toggle::make('collapsed')
                ->label('Initially Collapsed')
                ->helperText('Start collapsed when collapsible'),
            
            TextInput::make('description')
                ->label('Description')
                ->placeholder('Optional description'),
        ];
    }

    protected static function getGridConfig(): array
    {
        return [
            Select::make('columns')
                ->label('Number of Columns')
                ->options([
                    1 => '1 Column',
                    2 => '2 Columns', 
                    3 => '3 Columns',
                    4 => '4 Columns',
                ])
                ->default(2)
                ->required(),
        ];
    }

    protected static function getTabsConfig(): array
    {
        return [
            Repeater::make('tabs')
                ->label('Tabs')
                ->schema([
                    TextInput::make('label')
                        ->label('Tab Label')
                        ->required(),
                    
                    TextInput::make('icon')
                        ->label('Tab Icon (optional)')
                        ->placeholder('heroicon-o-document'),
                ])
                ->collapsible()
                ->itemLabel(fn (array $state): ?string => $state['label'] ?? null)
                ->minItems(1),
        ];
    }

    protected static function getTextInputConfig(): array
    {
        return [
            TextInput::make('name')
                ->label('Field Name')
                ->required()
                ->rules(['alpha_dash'])
                ->placeholder('field_name'),
            
            TextInput::make('label')
                ->label('Label')
                ->required()
                ->placeholder('Field Label'),
            
            TextInput::make('placeholder')
                ->label('Placeholder')
                ->placeholder('Enter text...'),
            
            TextInput::make('helperText')
                ->label('Helper Text')
                ->placeholder('Additional help text'),
            
            Toggle::make('required')
                ->label('Required Field'),
            
            TextInput::make('maxLength')
                ->label('Max Length')
                ->numeric()
                ->placeholder('255'),
        ];
    }

    protected static function getTextareaConfig(): array
    {
        return [
            TextInput::make('name')
                ->label('Field Name')
                ->required()
                ->rules(['alpha_dash'])
                ->placeholder('field_name'),
            
            TextInput::make('label')
                ->label('Label')
                ->required()
                ->placeholder('Field Label'),
            
            TextInput::make('placeholder')
                ->label('Placeholder')
                ->placeholder('Enter text...'),
            
            TextInput::make('helperText')
                ->label('Helper Text')
                ->placeholder('Additional help text'),
            
            Toggle::make('required')
                ->label('Required Field'),
            
            TextInput::make('rows')
                ->label('Number of Rows')
                ->numeric()
                ->default(3)
                ->placeholder('3'),
            
            TextInput::make('maxLength')
                ->label('Max Length')
                ->numeric()
                ->placeholder('500'),
        ];
    }

    protected static function getRichEditorConfig(): array
    {
        return [
            TextInput::make('name')
                ->label('Field Name')
                ->required()
                ->rules(['alpha_dash'])
                ->placeholder('field_name'),
            
            TextInput::make('label')
                ->label('Label')
                ->required()
                ->placeholder('Field Label'),
            
            TextInput::make('helperText')
                ->label('Helper Text')
                ->placeholder('Additional help text'),
            
            Toggle::make('required')
                ->label('Required Field'),
        ];
    }

    protected static function getSelectConfig(): array
    {
        return [
            TextInput::make('name')
                ->label('Field Name')
                ->required()
                ->rules(['alpha_dash'])
                ->placeholder('field_name'),
            
            TextInput::make('label')
                ->label('Label')
                ->required()
                ->placeholder('Field Label'),
            
            Textarea::make('options')
                ->label('Options (JSON)')
                ->required()
                ->rows(5)
                ->placeholder('{"value1": "Label 1", "value2": "Label 2"}')
                ->helperText('JSON format: {"key": "display_value"}'),
            
            Toggle::make('required')
                ->label('Required Field'),
            
            Toggle::make('searchable')
                ->label('Searchable'),
            
            Toggle::make('multiple')
                ->label('Allow Multiple Selection'),
        ];
    }

    protected static function getToggleConfig(): array
    {
        return [
            TextInput::make('name')
                ->label('Field Name')
                ->required()
                ->rules(['alpha_dash'])
                ->placeholder('field_name'),
            
            TextInput::make('label')
                ->label('Label')
                ->required()
                ->placeholder('Field Label'),
            
            TextInput::make('helperText')
                ->label('Helper Text')
                ->placeholder('Additional help text'),
            
            Toggle::make('default')
                ->label('Default Value (On/Off)'),
        ];
    }

    protected static function getDateConfig(): array
    {
        return [
            TextInput::make('name')
                ->label('Field Name')
                ->required()
                ->rules(['alpha_dash'])
                ->placeholder('field_name'),
            
            TextInput::make('label')
                ->label('Label')
                ->required()
                ->placeholder('Field Label'),
            
            TextInput::make('format')
                ->label('Date Format')
                ->default('Y-m-d')
                ->placeholder('Y-m-d'),
            
            Toggle::make('required')
                ->label('Required Field'),
        ];
    }

    protected static function getFileConfig(): array
    {
        return [
            TextInput::make('name')
                ->label('Field Name')
                ->required()
                ->rules(['alpha_dash'])
                ->placeholder('field_name'),
            
            TextInput::make('label')
                ->label('Label')
                ->required()
                ->placeholder('Field Label'),
            
            Textarea::make('acceptedTypes')
                ->label('Accepted File Types')
                ->placeholder('image/jpeg,image/png,application/pdf')
                ->helperText('Comma-separated MIME types'),
            
            TextInput::make('maxSize')
                ->label('Max File Size (KB)')
                ->numeric()
                ->default(2048)
                ->placeholder('2048'),
            
            Toggle::make('required')
                ->label('Required Field'),
        ];
    }

    protected static function getTagsConfig(): array
    {
        return [
            TextInput::make('name')
                ->label('Field Name')
                ->required()
                ->rules(['alpha_dash'])
                ->placeholder('field_name'),
            
            TextInput::make('label')
                ->label('Label')
                ->required()
                ->placeholder('Field Label'),
            
            TextInput::make('helperText')
                ->label('Helper Text')
                ->placeholder('Additional help text'),
        ];
    }

    /**
     * Convert builder data to JSON schema
     */
    public static function convertToJsonSchema(array $builderData): array
    {
        $fields = [];

        foreach ($builderData as $block) {
            $type = $block['type'];
            $data = $block['data'];

            switch ($type) {
                case 'section':
                    $fields[] = [
                        'type' => 'section',
                        'label' => $data['label'],
                        'collapsible' => $data['collapsible'] ?? false,
                        'collapsed' => $data['collapsed'] ?? false,
                        'fields' => [], // Will be populated by nested components
                    ];
                    break;

                case 'grid':
                    $fields[] = [
                        'type' => 'grid',
                        'columns' => $data['columns'] ?? 2,
                        'fields' => [], // Will be populated by nested components
                    ];
                    break;

                case 'tabs':
                    $tabsConfig = [
                        'type' => 'tabs',
                        'name' => 'dynamic_tabs',
                        'tabs' => []
                    ];
                    
                    foreach ($data['tabs'] ?? [] as $tab) {
                        $tabsConfig['tabs'][] = [
                            'label' => $tab['label'],
                            'icon' => $tab['icon'] ?? null,
                            'fields' => [], // Will be populated by nested components
                        ];
                    }
                    
                    $fields[] = $tabsConfig;
                    break;

                case 'text':
                    $fields[] = [
                        'type' => 'text',
                        'name' => $data['name'],
                        'label' => $data['label'],
                        'required' => $data['required'] ?? false,
                        'maxLength' => $data['maxLength'] ?? null,
                        'placeholder' => $data['placeholder'] ?? null,
                        'helper' => $data['helperText'] ?? null,
                    ];
                    break;

                case 'textarea':
                    $fields[] = [
                        'type' => 'textarea',
                        'name' => $data['name'],
                        'label' => $data['label'],
                        'required' => $data['required'] ?? false,
                        'rows' => $data['rows'] ?? 3,
                        'maxLength' => $data['maxLength'] ?? null,
                        'placeholder' => $data['placeholder'] ?? null,
                        'helper' => $data['helperText'] ?? null,
                    ];
                    break;

                case 'select':
                    $options = [];
                    if (!empty($data['options'])) {
                        $options = json_decode($data['options'], true) ?? [];
                    }
                    
                    $fields[] = [
                        'type' => 'select',
                        'name' => $data['name'],
                        'label' => $data['label'],
                        'required' => $data['required'] ?? false,
                        'options' => $options,
                        'searchable' => $data['searchable'] ?? false,
                        'multiple' => $data['multiple'] ?? false,
                    ];
                    break;

                case 'toggle':
                    $fields[] = [
                        'type' => 'toggle',
                        'name' => $data['name'],
                        'label' => $data['label'],
                        'default' => $data['default'] ?? false,
                        'helper' => $data['helperText'] ?? null,
                    ];
                    break;

                case 'date':
                    $fields[] = [
                        'type' => 'date',
                        'name' => $data['name'],
                        'label' => $data['label'],
                        'required' => $data['required'] ?? false,
                        'format' => $data['format'] ?? 'Y-m-d',
                    ];
                    break;

                case 'file':
                    $acceptedTypes = [];
                    if (!empty($data['acceptedTypes'])) {
                        $acceptedTypes = array_map('trim', explode(',', $data['acceptedTypes']));
                    }
                    
                    $fields[] = [
                        'type' => 'file',
                        'name' => $data['name'],
                        'label' => $data['label'],
                        'required' => $data['required'] ?? false,
                        'acceptedTypes' => $acceptedTypes,
                        'maxSize' => $data['maxSize'] ?? 2048,
                    ];
                    break;

                case 'tags':
                    $fields[] = [
                        'type' => 'tags',
                        'name' => $data['name'],
                        'label' => $data['label'],
                        'helper' => $data['helperText'] ?? null,
                    ];
                    break;
            }
        }

        return [
            'fields' => $fields,
            'generated_at' => now()->toISOString(),
            'version' => '1.0.0',
        ];
    }

    /**
     * Convert JSON schema back to builder data (reverse operation)
     */
    public static function convertSchemaToBuilderData(array $fields): array
    {
        $builderData = [];

        foreach ($fields as $field) {
            $type = $field['type'];
            
            switch ($type) {
                case 'section':
                    $builderData[] = [
                        'type' => 'section',
                        'data' => [
                            'label' => $field['label'],
                            'collapsible' => $field['collapsible'] ?? false,
                            'collapsed' => $field['collapsed'] ?? false,
                            'description' => $field['description'] ?? '',
                        ]
                    ];
                    break;

                case 'grid':
                    $builderData[] = [
                        'type' => 'grid',
                        'data' => [
                            'columns' => $field['columns'] ?? 2,
                        ]
                    ];
                    break;

                case 'tabs':
                    $tabsData = [];
                    foreach ($field['tabs'] ?? [] as $tab) {
                        $tabsData[] = [
                            'label' => $tab['label'],
                            'icon' => $tab['icon'] ?? null,
                        ];
                    }
                    
                    $builderData[] = [
                        'type' => 'tabs',
                        'data' => [
                            'tabs' => $tabsData,
                        ]
                    ];
                    break;

                case 'text':
                    $builderData[] = [
                        'type' => 'text',
                        'data' => [
                            'name' => $field['name'],
                            'label' => $field['label'],
                            'required' => $field['required'] ?? false,
                            'maxLength' => $field['maxLength'] ?? null,
                            'placeholder' => $field['placeholder'] ?? null,
                            'helperText' => $field['helper'] ?? null,
                        ]
                    ];
                    break;

                case 'textarea':
                    $builderData[] = [
                        'type' => 'textarea',
                        'data' => [
                            'name' => $field['name'],
                            'label' => $field['label'],
                            'required' => $field['required'] ?? false,
                            'rows' => $field['rows'] ?? 3,
                            'maxLength' => $field['maxLength'] ?? null,
                            'placeholder' => $field['placeholder'] ?? null,
                            'helperText' => $field['helper'] ?? null,
                        ]
                    ];
                    break;

                case 'select':
                    $builderData[] = [
                        'type' => 'select',
                        'data' => [
                            'name' => $field['name'],
                            'label' => $field['label'],
                            'required' => $field['required'] ?? false,
                            'options' => json_encode($field['options'] ?? []),
                            'searchable' => $field['searchable'] ?? false,
                            'multiple' => $field['multiple'] ?? false,
                        ]
                    ];
                    break;

                case 'toggle':
                    $builderData[] = [
                        'type' => 'toggle',
                        'data' => [
                            'name' => $field['name'],
                            'label' => $field['label'],
                            'default' => $field['default'] ?? false,
                            'helperText' => $field['helper'] ?? null,
                        ]
                    ];
                    break;

                case 'date':
                    $builderData[] = [
                        'type' => 'date',
                        'data' => [
                            'name' => $field['name'],
                            'label' => $field['label'],
                            'required' => $field['required'] ?? false,
                            'format' => $field['format'] ?? 'Y-m-d',
                        ]
                    ];
                    break;

                case 'file':
                    $builderData[] = [
                        'type' => 'file',
                        'data' => [
                            'name' => $field['name'],
                            'label' => $field['label'],
                            'required' => $field['required'] ?? false,
                            'acceptedTypes' => implode(',', $field['acceptedTypes'] ?? []),
                            'maxSize' => $field['maxSize'] ?? 2048,
                        ]
                    ];
                    break;

                case 'tags':
                    $builderData[] = [
                        'type' => 'tags',
                        'data' => [
                            'name' => $field['name'],
                            'label' => $field['label'],
                            'helperText' => $field['helper'] ?? null,
                        ]
                    ];
                    break;

                case 'rich_editor':
                    $builderData[] = [
                        'type' => 'rich_editor',
                        'data' => [
                            'name' => $field['name'],
                            'label' => $field['label'],
                            'required' => $field['required'] ?? false,
                            'helperText' => $field['helper'] ?? null,
                        ]
                    ];
                    break;
            }
        }

        return $builderData;
    }
}