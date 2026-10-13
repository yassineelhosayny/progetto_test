<?php

namespace App\Examples;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use App\Helpers\LocaleHelper;
use App\Services\TemplateManager;
use App\Services\JsonFormBuilder;

class PageFormWithDynamicTemplate
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(3)
                ->schema([
                    // COLONNA SINISTRA - TABS (2/3 dello spazio)
                    Tabs::make('Content Languages')
                        ->tabs(LocaleHelper::generateLanguageTabs([
                            'basic' => [
                                'title' => [
                                    'required' => true,
                                    'live' => true,
                                ],
                                'description' => [],
                            ],
                            'content' => [
                                'blocks' => [
                                    'template_dependent' => true,
                                ],
                            ],
                            'seo' => [
                                'meta_title' => [],
                                'meta_description' => [],
                                'meta_keywords' => [],
                            ],
                        ]))
                        ->columnSpan(2), // 2/3 dello spazio

                    // COLONNA DESTRA - GENERAL SETTINGS + DYNAMIC TEMPLATE (1/3 dello spazio)
                    Section::make('General Settings')
                        ->schema([
                            TextInput::make('slug')
                                ->label('URL Slug')
                                ->required()
                                ->unique('pages', 'slug')
                                ->rules(['alpha_dash'])
                                ->helperText('URL-friendly version of the title. Will be auto-generated if left empty.')
                                ->columnSpanFull(),

                            Select::make('template')
                                ->label('Page Template')
                                ->options(TemplateManager::getTemplateOptions())
                                ->default('default')
                                ->required()
                                ->live()
                                ->helperText('The template used to render this page'),

                            // DYNAMIC TEMPLATE SELECTION
                            Select::make('form_template_id')
                                ->label('Additional Form Fields')
                                ->options(JsonFormBuilder::getTemplateSelectOptions())
                                ->live()
                                ->helperText('Add dynamic fields from templates')
                                ->columnSpanFull(),

                            Select::make('status')
                                ->label('Status')
                                ->options([
                                    'draft' => 'Draft',
                                    'published' => 'Published',
                                    'scheduled' => 'Scheduled',
                                ])
                                ->default('draft')
                                ->required()
                                ->live(),

                            DateTimePicker::make('published_at')
                                ->label('Publish Date')
                                ->helperText('Leave empty to publish immediately when status is Published')
                                ->hidden(fn ($get) => $get('status') !== 'scheduled'),

                            Toggle::make('featured')
                                ->label('Featured Page')
                                ->helperText('Display this page prominently'),

                            Toggle::make('show_in_menu')
                                ->label('Show in Navigation')
                                ->default(true)
                                ->helperText('Include this page in the main navigation menu'),
                        ])
                        ->columnSpan(1), // 1/3 dello spazio
                ]),

            // DYNAMIC FIELDS SECTION - Based on selected template
            Section::make('Dynamic Fields')
                ->schema(function ($get) {
                    $templateId = $get('form_template_id');
                    
                    if (!$templateId) {
                        return [];
                    }

                    try {
                        return JsonFormBuilder::loadFromTemplateId($templateId);
                    } catch (\Exception $e) {
                        return [
                            \Filament\Forms\Components\Placeholder::make('template_error')
                                ->content('Template error: ' . $e->getMessage())
                                ->columnSpanFull(),
                        ];
                    }
                })
                ->visible(fn ($get) => !empty($get('form_template_id')))
                ->collapsible()
                ->collapsed(false)
                ->columnSpanFull(),
        ]);
    }
}

// Esempio per News
class NewsFormWithDynamicTemplate 
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(3)
                ->schema([
                    // Contenuto principale
                    Section::make('Content')
                        ->schema([
                            Tabs::make('language_tabs')
                                ->tabs(
                                    LocaleHelper::generateLanguageTabs([
                                        'Basic Information' => [
                                            'title' => ['required' => true, 'live' => true],
                                            'excerpt' => ['rows' => 3, 'maxLength' => 300],
                                            'description' => ['rows' => 4, 'maxLength' => 500],
                                        ],
                                        'Content Blocks' => [
                                            'blocks' => ['blocks' => [], 'template_dependent' => false]
                                        ],
                                        'SEO & Meta' => [
                                            'meta_title' => ['maxLength' => 60],
                                            'meta_description' => ['rows' => 3, 'maxLength' => 160],
                                            'meta_keywords' => ['placeholder' => 'keyword1, keyword2, keyword3']
                                        ]
                                    ])
                                ),
                        ])
                        ->columnSpan(2),

                    // Sidebar
                    Section::make('Settings')
                        ->schema([
                            TextInput::make('slug')->required(),
                            
                            Select::make('category')
                                ->options(['general' => 'General', 'tech' => 'Technology']),
                            
                            // Template dinamico per news
                            Select::make('news_template_id')
                                ->label('News Template')
                                ->options(function () {
                                    return JsonFormBuilder::getTemplateSelectOptions();
                                })
                                ->live()
                                ->helperText('Add custom fields to this news'),

                            Toggle::make('featured')->label('Featured News'),
                            
                            Select::make('status')
                                ->options(['draft' => 'Draft', 'published' => 'Published'])
                                ->default('draft'),
                        ])
                        ->columnSpan(1),
                ]),

            // Dynamic Template Fields
            Section::make('Additional Fields')
                ->schema(function ($get) {
                    $templateId = $get('news_template_id');
                    
                    if (!$templateId) {
                        return [];
                    }

                    try {
                        return JsonFormBuilder::loadFromTemplateId($templateId);
                    } catch (\Exception $e) {
                        return [
                            \Filament\Forms\Components\Placeholder::make('error')
                                ->content('Error: ' . $e->getMessage()),
                        ];
                    }
                })
                ->visible(fn ($get) => !empty($get('news_template_id')))
                ->collapsible()
                ->columnSpanFull(),
        ]);
    }
}

// Esempio per una risorsa completamente nuova
class ContactFormResource
{
    public static function getFormSchema(): array
    {
        return [
            Section::make('Contact Form Settings')
                ->schema([
                    TextInput::make('form_name')
                        ->label('Form Name')
                        ->required()
                        ->placeholder('Contact Form 2024'),
                        
                    Select::make('contact_template_id')
                        ->label('Form Template')
                        ->options(function () {
                            // Solo template di categoria 'form'
                            return \App\Models\FormTemplate::where('category', 'form')
                                ->where('is_active', true)
                                ->pluck('label', 'id')
                                ->toArray();
                        })
                        ->required()
                        ->live()
                        ->helperText('Choose the fields for this contact form'),
                        
                    TextInput::make('email_recipient')
                        ->label('Send to Email')
                        ->email()
                        ->required()
                        ->placeholder('admin@example.com'),
                        
                    TextInput::make('success_message')
                        ->label('Success Message')
                        ->default('Thank you for your message!')
                        ->columnSpanFull(),
                ]),
                
            // Dynamic fields from template
            Section::make('Form Fields')
                ->schema(function ($get) {
                    $templateId = $get('contact_template_id');
                    
                    if (!$templateId) {
                        return [
                            \Filament\Forms\Components\Placeholder::make('select_template')
                                ->content('Please select a template to see the form fields.')
                                ->columnSpanFull(),
                        ];
                    }

                    try {
                        return JsonFormBuilder::loadFromTemplateId($templateId);
                    } catch (\Exception $e) {
                        return [
                            \Filament\Forms\Components\Placeholder::make('error')
                                ->content('Error loading template: ' . $e->getMessage())
                                ->columnSpanFull(),
                        ];
                    }
                })
                ->columnSpanFull()
                ->collapsible()
                ->collapsed(false),
        ];
    }
}