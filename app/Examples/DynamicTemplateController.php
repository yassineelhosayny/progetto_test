<?php

namespace App\Examples;

use App\Models\FormTemplate;
use App\Services\JsonFormBuilder;
use Illuminate\Http\Request;

class DynamicTemplateController
{
    /**
     * Carica un template e mostra i campi dinamicamente
     */
    public function loadTemplate(Request $request)
    {
        $templateId = $request->get('template_id');
        
        if (!$templateId) {
            return response()->json(['error' => 'Template ID required'], 400);
        }

        try {
            $template = FormTemplate::findOrFail($templateId);
            $fields = JsonFormBuilder::loadFromTemplate($template);
            
            return response()->json([
                'template' => $template,
                'fields' => $this->convertFieldsToArray($fields),
                'html' => $this->renderFieldsAsHtml($fields),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Salva i dati di un form con template dinamico
     */
    public function saveWithTemplate(Request $request)
    {
        $templateId = $request->get('template_id');
        $formData = $request->all();
        
        try {
            $template = FormTemplate::findOrFail($templateId);
            $schema = $template->schema;
            
            // Valida i dati secondo lo schema del template
            $templateData = [];
            foreach ($schema as $field) {
                $fieldName = $field['name'] ?? null;
                if ($fieldName && isset($formData[$fieldName])) {
                    $templateData[$fieldName] = $formData[$fieldName];
                }
            }

            // Salva nella tua tabella (esempio con pages)
            $page = new \App\Models\Page();
            $page->title = $formData['title'] ?? 'New Page';
            $page->slug = $formData['slug'] ?? str()->slug($page->title);
            $page->form_template_id = $templateId;
            $page->template_data = $templateData; // Questo sarà cast in JSON automaticamente
            $page->save();

            return response()->json([
                'success' => true,
                'page_id' => $page->id,
                'template_data' => $templateData,
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Mostra una pagina con i dati del template
     */
    public function showPageWithTemplate($pageId)
    {
        try {
            $page = \App\Models\Page::with('formTemplate')->findOrFail($pageId);
            
            // Se la pagina ha un template, renderizza i campi dinamici
            if ($page->form_template_id && $page->formTemplate) {
                $dynamicFields = $this->renderTemplateData($page->formTemplate, $page->template_data);
                
                return view('pages.show-with-template', [
                    'page' => $page,
                    'dynamicFields' => $dynamicFields,
                ]);
            }

            return view('pages.show', ['page' => $page]);

        } catch (\Exception $e) {
            abort(404, 'Page not found: ' . $e->getMessage());
        }
    }

    /**
     * API per ottenere le opzioni di template filtrate
     */
    public function getTemplateOptions(Request $request)
    {
        $category = $request->get('category', 'form');
        $isActive = $request->get('active', true);
        
        $templates = FormTemplate::query()
            ->when($category, fn($q) => $q->where('category', $category))
            ->when($isActive, fn($q) => $q->where('is_active', true))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->select('id', 'name', 'label', 'description', 'category')
            ->get();

        return response()->json($templates);
    }

    /**
     * Converte i campi Filament in array per API
     */
    private function convertFieldsToArray($fields): array
    {
        $result = [];
        foreach ($fields as $field) {
            if (method_exists($field, 'getName') && method_exists($field, 'getLabel')) {
                $result[] = [
                    'name' => $field->getName(),
                    'label' => $field->getLabel(),
                    'type' => class_basename($field),
                    'required' => method_exists($field, 'isRequired') ? $field->isRequired() : false,
                ];
            }
        }
        return $result;
    }

    /**
     * Renderizza i campi come HTML per preview
     */
    private function renderFieldsAsHtml($fields): string
    {
        $html = '<div class="dynamic-fields-preview">';
        foreach ($fields as $field) {
            if (method_exists($field, 'getName') && method_exists($field, 'getLabel')) {
                $html .= "<div class='field-preview'>";
                $html .= "<label>{$field->getLabel()}</label>";
                $html .= "<div class='field-type'>" . class_basename($field) . "</div>";
                $html .= "</div>";
            }
        }
        $html .= '</div>';
        return $html;
    }

    /**
     * Renderizza i dati salvati del template
     */
    private function renderTemplateData($template, $templateData): array
    {
        $rendered = [];
        $schema = $template->schema;
        
        foreach ($schema as $field) {
            $fieldName = $field['name'] ?? null;
            if ($fieldName && isset($templateData[$fieldName])) {
                $rendered[] = [
                    'label' => $field['label'] ?? $fieldName,
                    'name' => $fieldName,
                    'value' => $templateData[$fieldName],
                    'type' => $field['component'] ?? 'text',
                ];
            }
        }

        return $rendered;
    }
}

// Esempio di utilizzo con AJAX
/*
// Frontend JavaScript per caricare dinamicamente i template
function loadTemplate(templateId) {
    fetch(`/api/templates/load?template_id=${templateId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Template error:', data.error);
                return;
            }
            
            // Mostra i campi del template
            const container = document.getElementById('dynamic-fields');
            container.innerHTML = data.html;
            
            console.log('Template loaded:', data.template.name);
            console.log('Fields:', data.fields);
        })
        .catch(error => console.error('Error:', error));
}

// Salva il form con template
function saveFormWithTemplate(formData, templateId) {
    const payload = {
        ...formData,
        template_id: templateId
    };
    
    fetch('/api/templates/save', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(payload)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = `/pages/${data.page_id}`;
        } else {
            alert('Error: ' + data.error);
        }
    });
}
*/