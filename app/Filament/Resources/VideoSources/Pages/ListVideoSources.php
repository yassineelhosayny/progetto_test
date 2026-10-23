<?php

namespace App\Filament\Resources\VideoSources\Pages;

use App\Filament\Resources\VideoSources\Schemas\VideoSourceForm;
use App\Filament\Resources\VideoSources\VideoSourceResource;
use App\Filament\Resources\Videos\VideoResource;
use App\Models\Video;
use App\Models\VideoSource;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ListVideoSources extends ListRecords
{
    protected static string $resource = VideoSourceResource::class;

    // Identifica il Video del quale stiamo visualizzando le versioni.
    public int $videoId;

    // Serve per costruire i link di ritorno alla lista e al form del Video.
    public int $videoDemoId;

    public function mount(): void
    {
        // L'URL è /admin/video-sources?video=ID. Il parametro identifica il
        // Video logico, non una singola versione.
        $this->videoId = (int) request()->query('video');

        $video = $this->getVideo();
        $this->videoDemoId = (int) $video
            ->videoSubcategory
            ->videoCategory
            ->video_demo_id;

        Gate::authorize('view', $video);

        parent::mount();
    }

    public function getTitle(): string
    {
        return 'Versioni del video: ' . $this->getVideo()->name.'.';
    }

    // Mostra esclusivamente le versioni del Video ricevuto nella query string.
    protected function getTableQuery(): Builder
    {
        return VideoSource::query()
            ->where('video_id', $this->videoId);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('backToVideos')
                ->label('Torna ai video')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(VideoResource::getUrl('index', [
                    'videoDemo' => $this->videoDemoId,
                ])),

            Action::make('editVideoData')
                ->label('Modifica dati video')
                ->icon('heroicon-o-pencil-square')
                ->url(VideoResource::getUrl('edit', [
                    'record' => $this->videoId,
                    'videoDemo' => $this->videoDemoId,
                ])),

            Action::make('createVersion')
                ->label('Aggiungi versione')
                ->icon('heroicon-o-plus')
                // Passa l'ID del Video al form per validare l'unicità della
                // coppia provider/external_id prima del salvataggio.
                ->schema(VideoSourceForm::components($this->videoId))
                ->action(function (array $data, Action $action): void {
                    $video = $this->getVideo();

                    Gate::authorize('update', $video);

                    try {
                        DB::transaction(function () use ($data, $video): void {
                            // Blocca le versioni del Video durante il cambio della
                            // versione corrente, evitando due correnti simultanee.
                            $video->sources()->lockForUpdate()->get();

                            $nextSortOrder = ((int) $video->sources()->max('sort_order')) + 1;

                            $video->sources()->update(['is_current' => false]);

                            $video->sources()->create([
                                ...VideoSourceForm::toDatabase($data),
                                'is_current' => true,
                                'sort_order' => $nextSortOrder,
                            ]);
                        });
                    } catch (UniqueConstraintViolationException) {
                        // Il controllo protegge anche da due richieste inviate
                        // contemporaneamente dopo la validazione del form.
                        Notification::make()
                            ->title('Versione già presente')
                            ->body('Questo ID video è già stato aggiunto per la piattaforma selezionata.')
                            ->danger()
                            ->send();

                        $action->halt();

                        return;
                    }

                    $action->success();
                })
                ->successNotificationTitle('Versione aggiunta correttamente'),
        ];
    }

    private function getVideo(): Video
    {
        return Video::query()
            ->with('videoSubcategory.videoCategory')
            ->findOrFail($this->videoId);
    }
}
