# Dynamic Templates System - Usage Guide

Il sistema di template dinamici ti permette di creare form personalizzati dall'interfaccia di amministrazione e utilizzarli in qualsiasi risorsa Filament.

## Panoramica del Sistema

### Componenti Principali
1. **FormTemplate Model** - Memorizza i template nel database
2. **FormTemplateBuilder Service** - Interfaccia drag&drop per creare template
3. **JsonFormBuilder Extended** - Carica e renderizza i template
4. **Admin Interface** - Gestisce i template tramite Filament

## Come Utilizzare i Template

### 1. Creare un Template

```php
// Vai su /admin/form-templates/create
// Usa l'interfaccia drag&drop per aggiungere campi:
// - Text Input, Textarea, Rich Editor
// - Select, Toggle, Date Picker
// - File Upload, Tags Input
// - Section, Grid, Tabs per il layout

// Salva il template con:
// - Nome e descrizione
// - Categoria (form, page, news, etc.)
// - Stato attivo/inattivo
```

### 2. Integrare in una Risorsa Filament

```php
use App\Services\JsonFormBuilder;

// Nel tuo PageResource/NewsResource/etc.
public static function form(Form $form): Form
{
    return $form->schema([
        // I tuoi campi esistenti...
        TextInput::make('title')->required(),
        Textarea::make('description'),
        
        // Aggiungi il selettore di template
        Select::make('form_template_id')
            ->label('Template Aggiuntivo')
            ->options(JsonFormBuilder::getTemplateSelectOptions())
            ->live(),
            
        // Sezione per i campi dinamici
        Section::make('Campi Template')
            ->schema(function ($get) {
                $templateId = $get('form_template_id');
                
                if (!$templateId) {
                    return [];
                }
                
                return JsonFormBuilder::loadFromTemplateId($templateId);
            })
            ->visible(fn ($get) => !empty($get('form_template_id')))
            ->collapsible(),
    ]);
}
```

### 3. Aggiungere Campi al Database

```bash
# Crea migrazione per aggiungere i campi template
php artisan make:migration add_template_fields_to_pages_table

# Aggiungi nella migrazione:
$table->json('template_data')->nullable();
$table->unsignedBigInteger('form_template_id')->nullable();
$table->foreign('form_template_id')->references('id')->on('form_templates');
```

### 4. Aggiornare il Model

```php
class Page extends Model
{
    protected $fillable = [
        // campi esistenti...
        'form_template_id',
        'template_data',
    ];

    protected $casts = [
        'template_data' => 'array',
    ];

    // Relazione
    public function formTemplate()
    {
        return $this->belongsTo(FormTemplate::class);
    }
    
    // Helper methods
    public function getTemplateValue($key, $default = null)
    {
        return data_get($this->template_data, $key, $default);
    }
    
    public function hasDynamicTemplate(): bool
    {
        return !is_null($this->form_template_id);
    }
}
```

### 5. Salvare i Dati del Template

```php
// Nel tuo CreatePage o EditPage
protected function mutateFormDataBeforeCreate(array $data): array
{
    if (isset($data['form_template_id']) && $data['form_template_id']) {
        $templateData = [];
        
        $template = FormTemplate::find($data['form_template_id']);
        $schema = $template->schema;
        
        // Estrai i dati del template dai dati del form
        foreach ($schema as $field) {
            $fieldName = $field['name'] ?? null;
            if ($fieldName && isset($data[$fieldName])) {
                $templateData[$fieldName] = $data[$fieldName];
                unset($data[$fieldName]); // Rimuovi dal data principale
            }
        }
        
        $data['template_data'] = $templateData;
    }

    return $data;
}
```

### 6. Mostrare i Dati nella Vista

```php
// Nel controller
public function show(Page $page)
{
    $dynamicFields = [];
    
    if ($page->hasDynamicTemplate()) {
        $dynamicFields = $page->getRenderedTemplateFields();
    }
    
    return view('pages.show', compact('page', 'dynamicFields'));
}
```

```blade
{{-- Nella vista --}}
@if($page->hasDynamicTemplate() && $dynamicFields)
    <section class="dynamic-fields">
        <h3>Informazioni Aggiuntive</h3>
        @foreach($dynamicFields as $field)
            <div class="field">
                <label>{{ $field['label'] }}</label>
                <div class="value">
                    @switch($field['type'])
                        @case('RichEditor')
                            {!! $field['value'] !!}
                            @break
                        @case('FileUpload')
                            <a href="{{ Storage::url($field['value']) }}">
                                Download {{ basename($field['value']) }}
                            </a>
                            @break
                        @case('Toggle')
                            {{ $field['value'] ? 'Sì' : 'No' }}
                            @break
                        @default
                            {{ $field['value'] }}
                    @endswitch
                </div>
            </div>
        @endforeach
    </section>
@endif
```

## Esempi Pratici

### Esempio 1: Pagina Eventi con Template
```php
// Template "Event Details" con campi:
// - event_date (DatePicker)
// - event_location (TextInput) 
// - event_price (TextInput)
// - event_organizer (Select)
// - is_featured (Toggle)

// Uso nella pagina:
echo $page->getTemplateValue('event_date'); // "2024-03-15"
echo $page->getTemplateValue('event_location'); // "Milano"
echo $page->getTemplateValue('is_featured') ? 'Evento in evidenza' : '';
```

### Esempio 2: News con Template Giornalista
```php
// Template "Journalist Info" con campi:
// - author_bio (RichEditor)
// - author_social (TagsInput) 
// - reading_time (TextInput)
// - difficulty_level (Select)

// Uso:
$news->setTemplateValue('reading_time', '5 minuti');
$news->save();
```

### Esempio 3: Form di Contatto Dinamico
```php
// Template "Contact Form Fields" con:
// - phone_required (Toggle)
// - company_field (Toggle) 
// - message_placeholder (TextInput)
// - custom_fields (Repeater)

// Il form si adatta automaticamente basandosi sul template
```

## API Endpoints (Opzionale)

```php
// routes/api.php
Route::get('/templates', [TemplateController::class, 'index']);
Route::get('/templates/{id}', [TemplateController::class, 'show']);
Route::get('/templates/{id}/fields', [TemplateController::class, 'getFields']);

// Uso AJAX
fetch('/api/templates/1/fields')
    .then(response => response.json())
    .then(fields => {
        // Renderizza i campi dinamicamente
    });
```

## Filtri e Query Avanzate

```php
// Trova pagine con template specifico
$pagesWithEvents = Page::whereHas('formTemplate', function($query) {
    $query->where('name', 'event-details');
})->get();

// Cerca nelle template_data
$featuredEvents = Page::whereJsonContains('template_data->is_featured', true)->get();

// Template per categoria
$formTemplates = FormTemplate::byCategory('form')->active()->get();
```

## Best Practices

1. **Naming**: Usa nomi descrittivi per i template (`event-details`, `contact-form`, `author-info`)
2. **Categorie**: Organizza i template per tipo (`form`, `page`, `content`, `seo`)
3. **Validazione**: Implementa validazione custom per campi critici
4. **Performance**: Usa `with(['formTemplate'])` per evitare N+1 queries
5. **Versioning**: Mantieni versioni dei template per compatibility
6. **Backup**: Esporta i template importanti in JSON

## Troubleshooting

### Template non carica
- Verifica che il template sia attivo (`is_active = true`)
- Controlla che lo schema JSON sia valido
- Assicurati che la relazione `formTemplate()` funzioni

### Campi non si salvano
- Controlla che i nomi dei campi nel template corrispondano
- Verifica che il cast `'template_data' => 'array'` sia presente
- Usa `mutateFormDataBeforeCreate()` per processare i dati

### Errori di rendering
- Usa try/catch quando carichi i template
- Gestisci campi mancanti con valori di default
- Verifica i permessi sui file caricati

## Estensioni Future

- **Template Marketplace**: Condivisione template tra progetti
- **A/B Testing**: Template alternativi per testing
- **Conditional Fields**: Campi che appaiono solo con certe condizioni
- **Multi-tenant**: Template per tenant specifici
- **Import/Export**: Backup e sincronizzazione template