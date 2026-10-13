<?php

namespace App\Filament\Clusters\News\Resources\News\Schemas;

use App\Filament\Schemas\Components\templates\news\PhotoGallery;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Slider;
use App\Helpers\LocaleHelper;
use Filament\Forms\Components\FileUpload;

class NewsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            // Layout principale: 2/3 contenuto + 1/3 sidebar
            Grid::make(3)
                ->schema([
                    // Colonna principale (2/3): Tabs multilingua
                    Section::make('Content')
                        ->schema([
                            Tabs::make('language_tabs')
                                ->tabs(
                                    LocaleHelper::generateLanguageTabs([
                                        'Basic Information' => [
                                            'title' => [
                                                'label' => 'Title',
                                                'required' => true,
                                                'maxLength' => 255,
                                                'live' => true
                                            ],
                                            'excerpt' => [
                                                'label' => 'Excerpt',
                                                'rows' => 3,
                                                'maxLength' => 300,
                                                'helperText' => 'Brief summary for news listings'
                                            ],
                                            'description' => [
                                                'label' => 'Description', 
                                                'rows' => 4,
                                                'maxLength' => 500,
                                                'helperText' => 'Detailed description for SEO and social sharing'
                                            ],
                                            'custom_editor' => [
                                                'label' => 'Editor',
                                            ],
                                        ],
                                        'Content Blocks' => [
                                            'blocks' => [
                                                'label' => 'News Content',
                                                'blocks' => [
                                                    PhotoGallery::make(),
                                                ],
                                                'template_dependent' => false
                                            ]
                                        ],
                                        'SEO & Meta' => [
                                            'meta_title' => [
                                                'label' => 'Meta Title',
                                                'maxLength' => 60,
                                                'helperText' => 'Optimal: 50-60 characters. Leave empty to use news title.'
                                            ],
                                            'meta_description' => [
                                                'label' => 'Meta Description',
                                                'rows' => 3,
                                                'maxLength' => 160,
                                                'helperText' => 'Optimal: 140-160 characters. Leave empty to use excerpt.'
                                            ],
                                            'meta_keywords' => [
                                                'label' => 'Meta Keywords',
                                                'helperText' => 'Comma-separated keywords. Generally not used by modern search engines.',
                                                'placeholder' => 'keyword1, keyword2, keyword3'
                                            ]
                                        ]
                                    ])
                                ),
                        ])
                        ->columnSpan(2),

                    // Sidebar (1/3): Settings e Publishing
                    Section::make('Settings')
                        ->schema([
                            // Slug field (shared across languages)
                            TextInput::make('slug')
                                ->label('URL Slug')
                                ->required()
                                ->maxLength(255)
                                ->unique('news', 'slug')
                                ->rules(['alpha_dash'])
                                ->helperText('URL-friendly version of the title. Will be auto-generated from title.'),

                            Section::make('News Details')
                                ->schema([
                                    DatePicker::make('publication_date')
                                        ->label('Publication Date')
                                        ->required(),
                                    Select::make('news_category_id')
                                        ->label('Category')
                                        ->relationship('category', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->helperText('Select news category'),

                                    TagsInput::make('tags')
                                        ->label('Tags')
                                        ->helperText('Add tags for better organization and search')
                                        ->separator(',')
                                        ->splitKeys(['Enter', 'Tab', ','])
                                        ->reorderable()
                                        ->dehydrateStateUsing(fn ($state) => is_array($state) ? $state : [])
                                        ->mutateDehydratedStateUsing(fn ($state) => is_array($state) ? $state : []),

                                    Toggle::make('featured')
                                        ->label('Featured News')
                                        ->helperText('Mark as featured to highlight in listings'),
                                ])
                                ->collapsible(),

                            Section::make('Publishing')
                                ->schema([
                                    Select::make('status')
                                        ->label('Status')
                                        ->options([
                                            'draft' => 'Draft',
                                            'published' => 'Published',
                                            'scheduled' => 'Scheduled',
                                        ])
                                        ->default('draft')
                                        ->required()
                                        ->live()
                                        ->helperText('Control the publication status of this news'),

                                    DateTimePicker::make('published_at')
                                        ->label('Publish Date')
                                        ->helperText('Leave empty to publish immediately when status is Published')
                                        ->hidden(fn ($get) => $get('status') !== 'scheduled'),
                                ])
                                ->collapsible(),
                            Section::make('Cover Image')
                                ->schema([
                                    FileUpload::make('excerpt_image')
                                        ->label('Excerpt Image')
                                        ->image()
                                        ->maxSize(2048) // 2MB
                                        ->directory('news/excerpts')
                                        ->disk('public')
                                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
                                        ->previewable()
                                        ->helperText('Image used in news listings and excerpts'),
                                ])
                        ])
                        ->columnSpan(1),
                ])
                ->columnSpanFull(),
        ]);
    }
}
