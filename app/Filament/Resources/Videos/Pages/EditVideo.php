<?php

namespace App\Filament\Resources\Videos\Pages;

use App\Filament\Resources\Videos\VideoResource;
use App\Models\VideoDemo;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Gate;

class EditVideo extends EditRecord
{
    protected static string $resource = VideoResource::class;

    protected ?bool $hasDatabaseTransactions = true;

    public int $videoDemoId;

    public function mount(int | string $record): void
    {
        // L'URL è /admin/videos/{record}/edit?videoDemo=ID: solo il record fa
        // parte della route, mentre il contenitore arriva dalla query string.
        $this->videoDemoId = (int) request()->query('videoDemo');

        Gate::authorize('view', $this->getVideoDemo());

        parent::mount($record);

        abort_unless(
            (int) $this->record->videoSubcategory->videoCategory->video_demo_id === $this->videoDemoId,
            404,
        );
    }

    // category_name e subcategory_name non sono colonne di videos: prima di
    // riempire il form le ricaviamo dalle relazioni del record.
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['video_demo_id'] = $this->videoDemoId;
        $data['category_name'] = $this->record->videoSubcategory->videoCategory->name;
        $data['subcategory_name'] = $this->record->videoSubcategory->name;

        return $data;
    }

    // Converte i nomi digitati nei relativi ID prima che Filament aggiorni
    // videos. Se cambia sottocategoria, il video viene messo in fondo ad essa.
    protected function mutateFormDataBeforeSave(array $data): array
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

        return [
            'video_subcategory_id' => $subcategory->getKey(),
            'name' => trim($data['name']),
            'is_active' => $data['is_active'] ?? false,
            'sort_order' => (int) $this->record->video_subcategory_id === (int) $subcategory->getKey()
                ? $this->record->sort_order
                : ((int) $subcategory->videos()->max('sort_order')) + 1,
        ];
    }

    // Mantiene l'eliminazione nella testata e, dopo il delete, torna alla
    // lista dei video dello stesso contenitore.
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->successRedirectUrl(VideoResource::getUrl('index', [
                    'videoDemo' => $this->videoDemoId,
                ])),
        ];
    }

    // Recupera il contenitore della route o restituisce 404 se non esiste.
    private function getVideoDemo(): VideoDemo
    {
        return VideoDemo::query()->findOrFail($this->videoDemoId);
    }
}
