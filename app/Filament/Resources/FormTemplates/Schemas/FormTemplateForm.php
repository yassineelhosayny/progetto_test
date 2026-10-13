<?php

namespace App\Filament\Resources\FormTemplates\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use App\Services\FormTemplateBuilder;
use App\Services\JsonFormBuilder;
use App\Models\FormTemplate;
use Illuminate\Support\Str;

class FormTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Layout principale: 2/3 builder + 1/3 settings
                Grid::make(3)
                    ->schema([
                        // Colonna principale (2/3): Template Builder
                        Section::make('Template Builder')
                            ->schema([
                                Tabs::make('builder_tabs')
                                    ->tabs([
                                        Tabs\Tab::make('Designer')
                                            ->icon('heroicon-o-squares-plus')
                                            ->schema([
                                                ...FormTemplateBuilder::getBuilderSchema(),
                                                
                                                // Campo nascosto per sincronizzare lo schema
                                                Hidden::make('schema')
                                                    ->default(fn($record) => $record ? $record->schema : [])
                                                    ->afterStateHydrated(function ($component, $state, $set) {
                                                        // Quando carichi un record esistente, popola il template_schema
                                                        if (!empty($state) && isset($state['fields'])) {
                                                            $builderData = FormTemplateBuilder::convertSchemaToBuilderData($state['fields']);
                                                            $set('template_schema', $builderData);
                                                        }
                                                    })
                                                    ->dehydrated(),
                                            ]),
                                        
                                        Tabs\Tab::make('JSON Schema')
                                            ->icon('heroicon-o-code-bracket')
                                            ->schema([
                                                Textarea::make('schema_json')
                                                    ->label('JSON Schema')
                                                    ->rows(20)
                                                    ->columnSpanFull()
                                                    ->helperText('Advanced users can edit the JSON schema directly')
                                                    ->live(debounce: 500)
                                                    ->afterStateUpdated(function ($state, $set) {
                                                        if (!empty($state)) {
                                                            try {
                                                                $decoded = json_decode($state, true);
                                                                if (json_last_error() === JSON_ERROR_NONE) {
                                                                    $set('schema', $decoded);
                                                                }
                                                            } catch (\Exception $e) {
                                                                // Invalid JSON, silently ignore
                                                            }
                                                        }
                                                    }),
                                            ]),
                                        
                                        Tabs\Tab::make('Preview')
                                            ->icon('heroicon-o-eye')
                                            ->schema([
                                                Section::make('Template Preview')
                                                    ->schema([
                                                        // Qui verrà renderizzata l'anteprima del form
                                                    ])
                                                    ->columnSpanFull(),
                                            ]),
                                    ]),
                            ])
                            ->columnSpan(2),

                        // Sidebar (1/3): Metadata e Settings
                        Section::make('Template Settings')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Template Name')
                                    ->required()
                                    ->unique('form_templates', 'name')
                                    ->rules(['alpha_dash'])
                                    ->live(debounce: 500)
                                    ->afterStateUpdated(function ($state, $set, $get) {
                                        if (empty($get('label'))) {
                                            $set('label', Str::title(str_replace(['_', '-'], ' ', $state)));
                                        }
                                    })
                                    ->helperText('Unique identifier for the template'),

                                TextInput::make('label')
                                    ->label('Display Label')
                                    ->required()
                                    ->helperText('Human-readable name'),

                                Textarea::make('description')
                                    ->label('Description')
                                    ->rows(3)
                                    ->helperText('Brief description of the template purpose'),

                                Select::make('category')
                                    ->label('Category')
                                    ->options(FormTemplate::getCategories())
                                    ->required()
                                    ->default('custom'),

                                TextInput::make('version')
                                    ->label('Version')
                                    ->default('1.0.0')
                                    ->rules(['regex:/^\d+\.\d+\.\d+$/'])
                                    ->helperText('Semantic version (e.g., 1.0.0)'),

                                Grid::make(2)
                                    ->schema([
                                        Toggle::make('is_active')
                                            ->label('Active')
                                            ->default(true)
                                            ->helperText('Template available for use'),

                                        Toggle::make('is_system')
                                            ->label('System Template')
                                            ->default(false)
                                            ->helperText('Protected from deletion')
                                            ->disabled(fn ($context) => $context === 'edit'),
                                    ]),

                                Section::make('Export/Import')
                                    ->schema([
                                        // Actions will be added later via page actions
                                    ])
                                    ->collapsible()
                                    ->collapsed(),
                            ])
                            ->columnSpan(1),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
