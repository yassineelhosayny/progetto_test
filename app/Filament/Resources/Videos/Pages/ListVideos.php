<?php

namespace App\Filament\Resources\Videos\Pages;

use App\Filament\Resources\VideoDemos\VideoDemoResource;
use App\Filament\Resources\Videos\VideoResource;
use App\Models\Video;
use App\Models\VideoDemo;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;

class ListVideos extends ListRecords
{
    protected static string $resource = VideoResource::class;

    public int $videoDemoId;

    public function mount(): void
    {
        // L'URL è /admin/videos?videoDemo=ID: il contenitore viene letto dalla
        // query string, mentre la route della Resource rimane standard.
        $this->videoDemoId = (int) request()->query('videoDemo');

        Gate::authorize('view', $this->getVideoDemo());
        Gate::authorize('viewAny', Video::class);

        parent::mount();
    }

    // Personalizza il titolo con il nome del contenitore corrente.
    public function getTitle(): string
    {
        return 'Video del contenitore: ' . $this->getVideoDemo()->title;
    }

    // Limita la tabella ai video appartenenti al contenitore indicato nella
    // route. La lista mostra un record per Video, non le singole versioni.
    protected function getTableQuery(): Builder
    {
        return Video::query()
            ->whereHas(
                'videoSubcategory.videoCategory',
                fn (Builder $query): Builder => $query
                    ->where('video_demo_id', $this->videoDemoId)
            )
            ->with([
                'videoSubcategory.videoCategory',
            ]);
    }

    // Aggiunge il ritorno ai contenitori e passa videoDemo alla pagina create.
    protected function getHeaderActions(): array
    {
        return [
            Action::make('backToContainers')
                ->label('Torna ai contenitori')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(VideoDemoResource::getUrl('index')),

            CreateAction::make()
                ->label('Aggiungi video')
                ->url(VideoResource::getUrl('create', [
                    'videoDemo' => $this->videoDemoId,
                ])),
        ];
    }

    // Recupera il contenitore usato da titolo, autorizzazione e pulsanti.
    private function getVideoDemo(): VideoDemo
    {
        return VideoDemo::query()->findOrFail($this->videoDemoId);
    }
}
