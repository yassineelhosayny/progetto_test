<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Repeater;

class SettingsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Settings')
                    ->tabs([
                        Tabs\Tab::make('Generali')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('site_name')
                                            ->label('Nome del sito')
                                            ->required()
                                            ->maxLength(255),

                                        TextInput::make('email')
                                            ->label('E-mail')
                                            ->email()
                                            ->maxLength(255),
                                    ]),

                                Textarea::make('site_description')
                                    ->label('Descrizione del sito')
                                    ->rows(3)
                                    ->maxLength(500),
                                RichEditor::make('informations')
                                    ->label('Informazioni sul sito')
                                    ->toolbarButtons([
                                        'bold',
                                        'italic',
                                        'underline',
                                        'strike',
                                        'bulletList',
                                        'orderedList',
                                        'link',
                                        'undo',
                                        'redo',
                                    ]),

                                Repeater::make('pdf_documents')
                                    ->label('Documenti PDF')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Nome Documento')
                                            ->maxLength(255),
                                        FileUpload::make('pdf')
                                            ->label('File PDF')
                                            ->disk('public')
                                            ->directory('settings/pdfs')
                                            ->acceptedFileTypes(['application/pdf'])
                                            ->maxSize(10240)
                                            ->helperText('Carica un PDF (max 10MB)'),
                                    ])
                                    ->grid(4)
                                    ->itemLabel(fn (array $state): ?string => isset($state['name']) ? strip_tags($state['name']) : 'Documento PDF')
                                    ->addActionLabel('Aggiungi PDF')
                                    ->collapsible()
                                    ->collapsed(true)
                                    ->columnSpanFull(),
                            ]),

                        Tabs\Tab::make('Immagini & Branding')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        FileUpload::make('favicon')
                                            ->label('Favicon')
                                            ->image()
                                            ->disk('public')
                                            ->acceptedFileTypes(['image/x-icon', 'image/vnd.microsoft.icon', 'image/png'])
                                            ->maxSize(1024)
                                            ->directory('settings/favicon')
                                            ->helperText('Carica un file .ico o .png (max 1MB)'),

                                        FileUpload::make('logo')
                                            ->label('Logo')
                                            ->image()
                                            ->disk('public')
                                            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/svg+xml'])
                                            ->maxSize(5120)
                                            ->directory('settings/logos')
                                            ->helperText('Carica un loo (max 5MB)'),

                                        FileUpload::make('og_image')
                                            ->label('Immagine Open Graph')
                                            ->image()
                                            ->disk('public')
                                            ->acceptedFileTypes(['image/png', 'image/jpeg'])
                                            ->maxSize(5120)
                                            ->directory('settings/og-images')
                                            ->helperText('raccomandata 1200×630px (max 5MB)'),
                                    ]),
                            ]),

                        Tabs\Tab::make('Social Media')
                            ->icon('heroicon-o-share')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('instagram_url')
                                            ->label('Instagram')
                                            ->url()
                                            ->maxLength(255)
                                            ->placeholder('https://instagram.com/yourprofile'),

                                        TextInput::make('facebook_url')
                                            ->label('Facebook')
                                            ->url()
                                            ->maxLength(255)
                                            ->placeholder('https://facebook.com/yourpage'),

                                        TextInput::make('linkedin_url')
                                            ->label('LinkedIn')
                                            ->url()
                                            ->maxLength(255)
                                            ->placeholder('https://linkedin.com/company/yourcompany'),

                                        TextInput::make('youtube_url')
                                            ->label('YouTube')
                                            ->url()
                                            ->maxLength(255)
                                            ->placeholder('https://youtube.com/@yourchannel'),

                                        TextInput::make('twitter_url')
                                            ->label('X (Twitter)')
                                            ->url()
                                            ->maxLength(255)
                                            ->placeholder('https://x.com/yourprofile'),
                                    ]),
                            ]),

                        Tabs\Tab::make('SEO & Analytics')
                            ->icon('heroicon-o-chart-bar')
                            ->schema([
                                TagsInput::make('meta_keywords')
                                    ->label('Meta Keywords')
                                    ->placeholder('Aggiungi keywords e premi Invio')
                                    ->helperText('Aggiungi keywords SEO per il sito'),

                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('google_analytics_id')
                                            ->label('Google Analytics ID')
                                            ->maxLength(255)
                                            ->placeholder('G-XXXXXXXXXX')
                                            ->helperText('Google Analytics 4 Measurement ID'),

                                        TextInput::make('google_tag_manager_id')
                                            ->label('Google Tag Manager ID')
                                            ->maxLength(255)
                                            ->placeholder('GTM-XXXXXXX')
                                            ->helperText('Google Tag Manager Container ID'),
                                    ]),
                            ]),

                        Tabs\Tab::make('Contatti')
                            ->icon('heroicon-o-phone')
                            ->schema([

                                Grid::make(2)
                                    ->schema([
                                        RichEditor::make('contacts')
                                            ->label('Informazioni')
                                            ->toolbarButtons([
                                                'bold',
                                                'italic',
                                                'underline',
                                                'strike',
                                                'bulletList',
                                                'orderedList',
                                                'link',
                                                'code',
                                                'h1','h2', 'h3',
                                                'redo',
                                                'undo',
                                            ]),

                                        RichEditor::make('opening_hours')
                                            ->label('Orari di apertura')
                                            ->toolbarButtons([
                                                'bold',
                                                'italic',
                                                'underline',
                                                'strike',
                                                'bulletList',
                                                'orderedList',
                                                'link',
                                                'code',
                                                'h1','h2', 'h3',
                                                'undo',
                                                'redo',
                                            ]),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
