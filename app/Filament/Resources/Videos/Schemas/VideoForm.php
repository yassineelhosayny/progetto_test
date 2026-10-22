<?php

namespace App\Filament\Resources\Videos\Schemas;

use App\Models\VideoCategory;
use App\Models\VideoSubcategory;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class VideoForm
{
    // Costruisce il form condiviso da CreateVideo ed EditVideo. Alcuni campi,
    // come category_name, sono campi di appoggio e non colonne di videos.
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('video_demo_id')
                    ->default(fn ($livewire): ?int => $livewire->videoDemoId ?? null),

                Section::make('Video')
                    ->schema([
                        TextInput::make('category_name')
                            ->label('Categoria')
                            ->datalist(fn (Get $get): array => self::categoryOptions($get('video_demo_id')))
                            ->helperText('Scrivi il nome della categoria oppure scegli un valore esistente.')
                            ->required()
                            ->live()
                            ->trim()
                            ->maxLength(255),

                        TextInput::make('subcategory_name')
                            ->label('Sottocategoria')
                            ->datalist(
                                fn (Get $get): array => self::subcategoryOptions(
                                    $get('video_demo_id'),
                                    $get('category_name'),
                                )
                            )
                            ->helperText('Facoltativa: se rimane vuota, viene usato il nome della categoria.')
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
                    ])
                    ->columns(2),

                Section::make('Prima versione')
                    ->schema(VideoSourceForm::components())
                    ->visible(fn (string $operation): bool => $operation === 'create')
                    ->columns(2),
            ]);
    }

    // Restituisce i nomi delle categorie già presenti nel contenitore per il
    // datalist. L'admin può comunque scrivere un nome nuovo.
    private static function categoryOptions(mixed $videoDemoId): array
    {
        if (blank($videoDemoId)) {
            return [];
        }

        return VideoCategory::query()
            ->where('video_demo_id', $videoDemoId)
            ->orderBy('sort_order')
            ->pluck('name')
            ->all();
    }

    // Propone soltanto le sottocategorie appartenenti alla categoria digitata
    // e allo stesso contenitore, evitando suggerimenti di altre demo.
    private static function subcategoryOptions(mixed $videoDemoId, mixed $categoryName): array
    {
        $categoryName = trim((string) $categoryName);

        if (blank($videoDemoId) || $categoryName === '') {
            return [];
        }

        return VideoSubcategory::query()
            ->whereHas(
                'videoCategory',
                fn (Builder $query): Builder => $query
                    ->where('video_demo_id', $videoDemoId)
                    ->where('name', $categoryName)
            )
            ->orderBy('sort_order')
            ->pluck('name')
            ->all();
    }
}
