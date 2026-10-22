<?php

namespace App\Filament\Resources\Videos\Pages;

use App\Filament\Resources\Videos\Schemas\VideoSourceForm;
use App\Filament\Resources\Videos\VideoResource;
use App\Models\VideoDemo;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Gate;

class CreateVideo extends CreateRecord
{
    protected static string $resource = VideoResource::class;

    protected static bool $canCreateAnother = false;

    protected ?bool $hasDatabaseTransactions = true;

    // ID del contenitore dal quale è stata aperta la pagina di creazione.
    public int $videoDemoId;

    // Dati della prima versione: vengono preparati prima della creazione del
    // video e usati in afterCreate(), quando l'ID del video è disponibile.
    /** @var array<string, mixed> */
    private array $sourceData = [];

    public function mount(): void
    {
        // L'URL è /admin/videos/create?videoDemo=ID: il contenitore viene
        // letto dalla query string senza cambiare la firma di CreateRecord.
        $this->videoDemoId = (int) request()->query('videoDemo');

        Gate::authorize('view', $this->getVideoDemo());

        parent::mount();
    }

    // Trasforma i campi di appoggio del form nei soli campi della tabella
    // videos. Categoria e sottocategoria vengono trovate o create qui.
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $videoDemo = $this->getVideoDemo();
        $categoryName = trim($data['category_name']);
        $subcategoryName = trim($data['subcategory_name'] ?? '');

        if ($subcategoryName === '') {
            // Regola concordata: senza sottocategoria si usa la categoria.
            $subcategoryName = $categoryName;
        }

        $category = $videoDemo->categories()->firstOrCreate(
            ['name' => $categoryName],
            ['is_active' => true],
        );

        $subcategory = $category->subcategories()->firstOrCreate(
            ['name' => $subcategoryName],
            [
                'is_active' => true,
                'sort_order' => ((int) $category->subcategories()->max('sort_order')) + 1,
            ],
        );

        $this->sourceData = VideoSourceForm::toDatabase($data);

        return [
            'video_subcategory_id' => $subcategory->getKey(),
            'name' => trim($data['name']),
            'is_active' => $data['is_active'] ?? false,
            'sort_order' => ((int) $subcategory->videos()->max('sort_order')) + 1,
        ];
    }

    // Filament esegue questo hook dopo aver creato il video. A questo punto
    // possiamo collegare la prima versione tramite Video::sources().
    protected function afterCreate(): void
    {
        $this->record->sources()->create([
            ...$this->sourceData,
            'is_current' => true,
            'sort_order' => 1,
        ]);
    }

    // Dopo la creazione apre la modifica dello stesso video, dove sono
    // disponibili anche la tabella e le azioni per le versioni.
    protected function getRedirectUrl(): string
    {
        return VideoResource::getUrl('edit', [
            'videoDemo' => $this->videoDemoId,
            'record' => $this->record,
        ]);
    }

    // Centralizza il caricamento del contenitore e restituisce 404 se l'ID
    // ricevuto nella route non esiste.
    private function getVideoDemo(): VideoDemo
    {
        return VideoDemo::query()->findOrFail($this->videoDemoId);
    }
}
