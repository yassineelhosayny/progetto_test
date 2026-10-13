<?php

namespace App\Filament\Clusters\News\Resources\NewsCategories\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ColorPicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use App\Helpers\LocaleHelper;

class NewsCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(3)
                ->schema([
                    // Colonna principale (2/3): Tabs multilingua
                    Section::make('Content')
                        ->schema([
                            Tabs::make('language_tabs')
                                ->tabs(
                                    LocaleHelper::generateLanguageTabs([
                                        'Base' => [
                                            'name' => [
                                                'label' => 'Nome',
                                                'required' => true,
                                                'maxLength' => 255,
                                                'live' => true
                                            ],
                                            'description' => [
                                                'label' => 'Descrizione',
                                                'rows' => 3,
                                                'maxLength' => 500,
                                                'helperText' => 'Descrizione della categoria (opzionale)'
                                            ],
                                        ],
                                    ])
                                ),
                        ])
                        ->columnSpan(2),

                    // Sidebar (1/3): Settings
                    Section::make('Impostazioni')
                        ->schema([
                            TextInput::make('slug')
                                ->label('Slug')
                                ->required()
                                ->unique('news_categories', 'slug')
                                ->maxLength(255)
                                ->rules(['alpha_dash'])
                                ->helperText('Verrà autogenerato dal nome, ma puoi modificarlo.'),
                            Toggle::make('is_active')
                                ->label('Attiva')
                                ->default(true),
                            ColorPicker::make('color')
                                ->label('Colore')
                                ->formatStateUsing(fn ($state) => $state)
                                ->dehydrateStateUsing(fn ($state) => $state)
                                ->helperText('Valore esadecimale o scegli dal picker.')
                                ->nullable()
                                ->extraInputAttributes(['type' => 'text']),
                        ])
                        ->columnSpan(1),
                ])
                ->columnSpanFull(),
        ]);
    }
}
