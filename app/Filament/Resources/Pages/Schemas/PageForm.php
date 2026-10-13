<?php

namespace App\Filament\Resources\Pages\Schemas;

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

class PageForm
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

                    // COLONNA DESTRA - GENERAL SETTINGS (1/3 dello spazio)
                    Section::make('Impostazioni')
                        ->schema([
                            TextInput::make('slug')
                                ->label('Slug')
                                ->required()
                                ->maxLength(255)
                                ->unique('pages', 'slug')
                                ->rules(['alpha_dash'])
                                ->helperText('Versione del titolo URL-friendly. Verrà generata automaticamente dal titolo italiano se lasciata vuota.'),

                            Select::make('template')
                                ->label('Template')
                                ->options(TemplateManager::getTemplateOptions())
                                ->default('default')
                                ->required()
                                ->live()
                                ->helperText('Seleziona il template per determinare i componenti disponibili.'),

                            Select::make('status')
                                ->label('Status')
                                ->options([
                                    'draft' => 'Bozza',
                                    'published' => 'Pubblicata',
                                    'scheduled' => 'Programmata',
                                ])
                                ->default('draft')
                                ->required()
                                ->live()
                                ->helperText('Controlla lo stato di pubblicazione di questa pagina.'),

                            DateTimePicker::make('published_at')
                                ->label('Data di pubblicazione')
                                ->helperText('Lascia vuoto per pubblicare immediatamente quando lo stato è Pubblicato.')
                                ->hidden(fn ($get) => $get('status') !== 'scheduled'),

                            Toggle::make('is_homepage')
                                ->label('Imposta come Homepage')
                                ->helperText('Può esserci una sola homepage. Attivando questa opzione, il flag di homepage verrà rimosso dalle altre pagine.'),
                        ])
                        ->columnSpan(1), // 1/3 dello spazio
                ])
                ->columnSpanFull(), // occupa tutta la larghezza
        ]);
    }
}