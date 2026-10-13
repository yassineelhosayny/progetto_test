<?php

return '<?php

namespace App\\Filament\\Clusters\\{{CLUSTER_NAME}}\\Resources\\{{RESOURCE_NAME}}\\Schemas;

use Filament\\Schemas\\Schema;
use Filament\\Forms\\Components\\TextInput;
use Filament\\Forms\\Components\\Textarea;
use Filament\\Forms\\Components\\Toggle;
use Filament\\Forms\\Components\\Select;
use Filament\\Schemas\\Components\\Section;
use Filament\\Schemas\\Components\\Grid;
use Filament\\Schemas\\Components\\Tabs;
use App\\Helpers\\LocaleHelper;
use App\\Services\\TemplateManager;

class {{RESOURCE_NAME}}Form
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            // Layout principale: 2/3 contenuto + 1/3 sidebar
            Grid::make(3)
                ->schema([
                    // Colonna principale (2/3): Tabs multilingua
                    Section::make(\'Content\')
                        ->schema([
                            Tabs::make(\'language_tabs\')
                                ->tabs(
                                    LocaleHelper::generateLanguageTabs([
                                        \'Basic Information\' => [
                                            \'name\' => [
                                                \'label\' => \'Title\',
                                                \'required\' => true,
                                                \'maxLength\' => 255,
                                                \'live\' => true
                                            ],
                                            \'excerpt\' => [
                                                \'label\' => \'Excerpt\',
                                                \'rows\' => 3,
                                                \'maxLength\' => 300,
                                                \'helperText\' => \'Brief summary for news listings\'
                                            ],
                                            \'description\' => [
                                                \'label\' => \'Description\',
                                                \'rows\' => 4,
                                                \'maxLength\' => 500,
                                                \'helperText\' => \'Detailed description for SEO and social sharing\'
                                            ],
                                        ],
                                        \'Content Blocks\' => [
                                            \'blocks\' => [
                                                \'template_dependent\' => true
                                            ]
                                        ],
                                        \'SEO & Meta\' => [
                                            \'meta_title\' => [
                                                \'label\' => \'Meta Title\',
                                                \'maxLength\' => 60,
                                                \'helperText\' => \'Optimal: 50-60 characters. Leave empty to use news title.\'
                                            ],
                                            \'meta_description\' => [
                                                \'label\' => \'Meta Description\',
                                                \'rows\' => 3,
                                                \'maxLength\' => 160,
                                                \'helperText\' => \'Optimal: 140-160 characters. Leave empty to use excerpt.\'
                                            ],
                                            \'meta_keywords\' => [
                                                \'label\' => \'Meta Keywords\',
                                                \'helperText\' => \'Comma-separated keywords. Generally not used by modern search engines.\',
                                                \'placeholder\' => \'keyword1, keyword2, keyword3\'
                                            ]
                                        ]
                                    ])
                                )
                        ])
                        ->columnSpan(2),
                    
                    // Sidebar (1/3): Configurazioni
                    Section::make(\'Settings\')
                        ->schema([
                            Toggle::make(\'is_active\')
                                ->label(\'Active\')
                                ->default(true),
                            Select::make(\'template\')
                                ->label(\'Template\')
                                ->options(TemplateManager::getTemplateOptions())
                                ->default(\'default\')
                                ->required()
                                ->live()
                                ->helperText(\'Seleziona il template per determinare i componenti disponibili\'),
                        ])
                        ->columnSpan(1),
                ])
                ->columnSpanFull()
        ]);
    }
}
';