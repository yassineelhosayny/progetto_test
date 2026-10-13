<?php

namespace App\Examples;

use App\Filament\Resources\PageResource;
use App\Models\Page;
use App\Services\JsonFormBuilder;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePageWithTemplate extends CreateRecord
{
    protected static string $resource = PageResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Se c'è un template selezionato, salva i dati del template in un campo JSON
        if (isset($data['form_template_id']) && $data['form_template_id']) {
            $templateData = [];
            
            try {
                $template = \App\Models\FormTemplate::findOrFail($data['form_template_id']);
                $schema = is_string($template->schema) 
                    ? json_decode($template->schema, true) 
                    : (is_array($template->schema) ? $template->schema : []);
                
                // Estrai solo i dati che appartengono al template
                foreach ($schema as $field) {
                    if (isset($field['name']) && isset($data[$field['name']])) {
                        $templateData[$field['name']] = $data[$field['name']];
                        // Rimuovi dal data principale per evitare errori di colonne inesistenti
                        unset($data[$field['name']]);
                    }
                }
                
                $data['template_data'] = json_encode($templateData);
            } catch (\Exception $e) {
                // Log dell'errore ma continua con la creazione
                \Log::error('Template data processing error: ' . $e->getMessage());
            }
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('preview_template')
                ->label('Preview Template')
                ->icon('heroicon-o-eye')
                ->visible(fn () => request()->has('template_id'))
                ->modalContent(function () {
                    $templateId = request('template_id');
                    if (!$templateId) return 'No template selected';
                    
                    try {
                        $template = \App\Models\FormTemplate::findOrFail($templateId);
                        return view('filament.pages.template-preview', ['template' => $template]);
                    } catch (\Exception $e) {
                        return 'Error loading template: ' . $e->getMessage();
                    }
                }),
        ];
    }
}