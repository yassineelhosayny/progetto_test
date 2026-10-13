<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use App\Services\JsonFormBuilder;
use App\Models\FormTemplate;

class DynamicTemplatePreviewResource extends Resource
{
    protected static ?string $model = FormTemplate::class;

    protected static ?string $navigationLabel = 'Template Preview';
    
    protected static bool $shouldRegisterNavigation = false; // Hidden from navigation

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Template Selection')
                    ->schema([
                        Select::make('template_id')
                            ->label('Select Template to Preview')
                            ->options(JsonFormBuilder::getTemplateSelectOptions())
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                if ($state) {
                                    $template = FormTemplate::find($state);
                                    if ($template) {
                                        // Load template fields dynamically
                                        $set('template_fields', $template->schema);
                                    }
                                }
                            }),
                    ]),

                Section::make('Template Preview')
                    ->schema(function ($get) {
                        $templateId = $get('template_id');
                        if (!$templateId) {
                            return [
                                \Filament\Forms\Components\Placeholder::make('no_template')
                                    ->label('')
                                    ->content('Please select a template to preview its fields.'),
                            ];
                        }

                        try {
                            return JsonFormBuilder::loadFromTemplateId($templateId);
                        } catch (\Exception $e) {
                            return [
                                \Filament\Forms\Components\Placeholder::make('error')
                                    ->label('Error')
                                    ->content('Error loading template: ' . $e->getMessage()),
                            ];
                        }
                    })
                    ->visible(fn ($get) => $get('template_id'))
                    ->columnSpanFull(),
            ]);
    }
}

// Esempio di utilizzo in una risorsa esistente
class ExampleUsageInResource
{
    /**
     * Esempio di come integrare template dinamici in una risorsa esistente
     */
    public static function getFormWithDynamicTemplate(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Settings')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('title')
                            ->label('Title')
                            ->required(),

                        Select::make('form_template')
                            ->label('Form Template')
                            ->options(JsonFormBuilder::getTemplateSelectOptions())
                            ->live()
                            ->helperText('Select a predefined template for additional fields'),
                    ]),

                Section::make('Dynamic Fields')
                    ->schema(function ($get) {
                        $templateId = $get('form_template');
                        
                        if (!$templateId) {
                            return [];
                        }

                        try {
                            return JsonFormBuilder::loadFromTemplateId($templateId);
                        } catch (\Exception $e) {
                            return [
                                \Filament\Forms\Components\Placeholder::make('template_error')
                                    ->content('Template error: ' . $e->getMessage()),
                            ];
                        }
                    })
                    ->visible(fn ($get) => !empty($get('form_template')))
                    ->columnSpanFull(),
            ]);
    }
}