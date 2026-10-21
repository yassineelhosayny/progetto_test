<?php

namespace App\Filament\Resources\VideoDemos\Pages;

use App\Filament\Resources\VideoDemos\VideoDemoResource;
use App\Models\Video;
use App\Models\VideoDemo;
use App\Models\VideoSubcategory;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\EmbeddedTable;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ManageVideoDemoVideos extends Page implements HasTable
{
    use InteractsWithRecord;
    use InteractsWithTable;

    protected static string $resource = VideoDemoResource::class;

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);

        abort_unless(VideoDemoResource::canView($this->getRecord()), 403);
        Gate::authorize('viewAny', Video::class);
    }

    public function getTitle(): string
    {
        return 'Video del contenitore: ' . $this->getVideoDemo()->title;
    }

    public function getBreadcrumb(): string
    {
        return 'Gestisci video';
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            EmbeddedTable::make(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Video::query()
                    ->whereHas(
                        'videoSubcategory.videoCategory',
                        fn (Builder $query): Builder =>
                            $query->where('video_demo_id', $this->getVideoDemo()->getKey())
                    )
                    ->with([
                        'videoSubcategory.videoCategory',
                        'currentSource',
                    ])
            )
            ->heading('Lista video')
            ->columns([
                TextColumn::make('videoSubcategory.videoCategory.name')
                    ->label('Categoria')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('videoSubcategory.name')
                    ->label('Sottocategoria')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('currentSource.provider')
                    ->label('Provider')
                    ->badge()
                    ->placeholder('Nessuna fonte'),

                TextColumn::make('currentSource.playback_url')
                    ->label('Link video')
                    ->url(
                        fn (Video $record): ?string => $record->currentSource?->playback_url,
                        shouldOpenInNewTab: true,
                    )
                    ->copyable()
                    ->limit(45)
                    ->placeholder('—'),

                IconColumn::make('is_active')
                    ->label('Visibile')
                    ->boolean(),
            ])
            ->headerActions([
                $this->makeCreateVideoAction(),
            ])
            ->defaultSort('sort_order')
            ->striped();
    }

    private function makeCreateVideoAction(): Action
    {
        return Action::make('createVideo')
            ->label('Aggiungi video')
            ->icon('heroicon-o-plus')
            ->modalHeading('Aggiungi video a ' . $this->getVideoDemo()->title)
            ->schema([
                TextInput::make('category_name')
                    ->label('Categoria')
                    // Suggerisce le categorie esistenti, ma permette anche
                    // di scriverne una nuova come nel progetto originale.
                    ->datalist(
                        fn (): array => $this->getVideoDemo()
                            ->categories()
                            ->orderBy('sort_order')
                            ->pluck('name')
                            ->all()
                    )
                    ->helperText('Se la categoria non esiste, verrà creata automaticamente.')
                    ->required()
                    ->live()
                    ->trim()
                    ->maxLength(255),

                TextInput::make('subcategory_name')
                    ->label('Sottocategoria')
                    // Mostra le sottocategorie della categoria digitata.
                    ->datalist(function (Get $get): array {
                        $categoryName = trim((string) $get('category_name'));

                        if ($categoryName === '') {
                            return [];
                        }

                        return VideoSubcategory::query()
                            ->whereHas(
                                'videoCategory',
                                fn (Builder $query): Builder => $query
                                    ->where('video_demo_id', $this->getVideoDemo()->getKey())
                                    ->where('name', $categoryName)
                            )
                            ->orderBy('sort_order')
                            ->pluck('name')
                            ->all();
                    })
                    ->helperText('Se la sottocategoria non esiste, verrà creata automaticamente.')
                    ->required()
                    ->trim()
                    ->maxLength(255),

                TextInput::make('name')
                    ->label('Nome video')
                    ->required()
                    ->trim()
                    ->maxLength(255),

                Toggle::make('is_active')
                    ->label('Visibile')
                    ->default(true),

                Section::make('Nuova versione')
                    ->schema([
                        Select::make('upload_method')
                            ->label('Tipo di caricamento')
                            ->options([
                                'manual' => 'Manuale',
                                'api' => 'Tramite API (prossimamente)',
                            ])
                            ->default('manual')
                            ->disableOptionWhen(fn (string $value): bool => $value === 'api')
                            ->required(),

                        Select::make('provider')
                            ->label('Piattaforma video')
                            ->options([
                                'vimeo' => 'Vimeo',
                                'youtube' => 'YouTube',
                                'bunny' => 'Bunny',
                            ])
                            ->live()
                            ->required(),

                        TextInput::make('source_label')
                            ->label('Nome versione')
                            ->trim()
                            ->maxLength(255),

                        TextInput::make('external_id')
                            ->label(fn (Get $get): string => match ($get('provider')) {
                                'youtube' => 'ID video YouTube',
                                default => 'ID video Vimeo',
                            })
                            ->visible(fn (Get $get): bool => in_array($get('provider'), ['vimeo', 'youtube'], true))
                            ->required(fn (Get $get): bool => in_array($get('provider'), ['vimeo', 'youtube'], true))
                            ->trim()
                            ->maxLength(255),

                        TextInput::make('vimeo_unlisted_code')
                            ->label('Codice Unlisted Vimeo')
                            ->visible(fn (Get $get): bool => $get('provider') === 'vimeo')
                            ->trim()
                            ->maxLength(255),

                        TextInput::make('source_url')
                            ->label('Link video Bunny')
                            ->visible(fn (Get $get): bool => $get('provider') === 'bunny')
                            ->required(fn (Get $get): bool => $get('provider') === 'bunny')
                            ->url(),

                        TextInput::make('thumbnail_url')
                            ->label('Thumbnail Bunny')
                            ->visible(fn (Get $get): bool => $get('provider') === 'bunny')
                            ->url(),

                        DateTimePicker::make('version_date')
                            ->label('Data versione')
                            ->default(now())
                            ->required(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ])
            ->action(function (array $data, Action $action): void {
                Gate::authorize('create', Video::class);

                DB::transaction(function () use ($data): void {
                    // Categoria e sottocategoria vengono recuperate oppure
                    // create qui: non hanno un form amministrativo separato.
                    $category = $this->getVideoDemo()->categories()->firstOrCreate(
                        ['name' => trim($data['category_name'])],
                        ['is_active' => true],
                    );

                    $subcategory = $category->subcategories()->firstOrCreate(
                        ['name' => trim($data['subcategory_name'])],
                        [
                            'is_active' => true,
                            'sort_order' => ((int) $category->subcategories()->max('sort_order')) + 1,
                        ],
                    );

                    $video = Video::query()->create([
                        'video_subcategory_id' => $subcategory->getKey(),
                        'name' => trim($data['name']),
                        'is_active' => $data['is_active'] ?? false,
                        'sort_order' => ((int) $subcategory->videos()->max('sort_order')) + 1,
                    ]);

                    $video->sources()->create([
                        'provider' => $data['provider'],
                        'label' => filled($data['source_label'] ?? null)
                            ? trim($data['source_label'])
                            : null,
                        'external_id' => in_array($data['provider'], ['vimeo', 'youtube'], true)
                            ? ($data['external_id'] ?? null)
                            : null,
                        'source_url' => $data['provider'] === 'bunny'
                            ? ($data['source_url'] ?? null)
                            : null,
                        'thumbnail_url' => $data['provider'] === 'bunny'
                            ? ($data['thumbnail_url'] ?? null)
                            : null,
                        'upload_method' => $data['upload_method'],
                        'upload_status' => 'ready',
                        'version_date' => $data['version_date'],
                        'is_current' => true,
                        'is_active' => true,
                        'sort_order' => 1,
                        'metadata' => $data['provider'] === 'vimeo'
                            && filled($data['vimeo_unlisted_code'] ?? null)
                            ? ['unlisted_code' => trim($data['vimeo_unlisted_code'])]
                            : null,
                    ]);
                });

                $action->success();
            })
            ->successNotificationTitle('Video creato correttamente');
    }

    private function getVideoDemo(): VideoDemo
    {
        /** @var VideoDemo $videoDemo */
        $videoDemo = $this->getRecord();

        return $videoDemo;
    }
}
