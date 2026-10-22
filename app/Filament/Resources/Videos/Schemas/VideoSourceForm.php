<?php

namespace App\Filament\Resources\Videos\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;

class VideoSourceForm
{
    /**
     * Campi condivisi dalla prima versione e dalle versioni successive.
     *
     * @return array<int, mixed>
     */
    public static function components(): array
    {
        return [
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

            TextInput::make('label')
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
        ];
    }

    /**
     * Converte i campi del form nelle colonne di video_sources.
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public static function toDatabase(array $data): array
    {
        // Il form contiene campi diversi per ciascun provider. Prima del
        // salvataggio conserviamo solo quelli pertinenti al provider scelto.
        return [
            'provider' => $data['provider'],
            'label' => filled($data['label'] ?? null) ? trim($data['label']) : null,
            'external_id' => in_array($data['provider'], ['vimeo', 'youtube'], true)
                ? ($data['external_id'] ?? null)
                : null,
            'source_url' => $data['provider'] === 'bunny' ? ($data['source_url'] ?? null) : null,
            'thumbnail_url' => $data['provider'] === 'bunny' ? ($data['thumbnail_url'] ?? null) : null,
            'upload_method' => $data['upload_method'],
            'upload_status' => 'ready',
            'version_date' => $data['version_date'],
            'is_active' => true,
            'metadata' => $data['provider'] === 'vimeo'
                && filled($data['vimeo_unlisted_code'] ?? null)
                    ? ['unlisted_code' => trim($data['vimeo_unlisted_code'])]
                    : null,
        ];
    }
}
