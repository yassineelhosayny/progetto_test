<?php

namespace App\Filament\Resources\VideoSources\Tables;

use App\Models\VideoSource;
use Filament\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Gate;

class VideoSourcesTable
{
    // Ogni riga rappresenta una versione/link dello stesso Video.
    public static function configure(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('label')
            ->columns([
                TextColumn::make('label')
                    ->label('Nome')
                    ->placeholder('Senza nome'),

                TextColumn::make('version_date')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('provider')
                    ->label('Tipo')
                    ->badge(),

                TextColumn::make('playback_url')
                    ->label('Link video')
                    ->formatStateUsing(fn (?string $state): string => filled($state) ? 'Apri video' : '—')
                    ->url(
                        fn (VideoSource $record): ?string => $record->playback_url,
                        shouldOpenInNewTab: true,
                    )
                    ->placeholder('—'),

                TextColumn::make('thumbnail_url')
                    ->label('Thumbnail')
                    ->formatStateUsing(fn (?string $state): string => filled($state) ? 'Apri thumbnail' : '—')
                    ->url(
                        fn (VideoSource $record): ?string => $record->thumbnail_url,
                        shouldOpenInNewTab: true,
                    )
                    ->placeholder('—'),

                IconColumn::make('is_current')
                    ->label('Video corrente')
                    ->boolean(),
            ])
            ->recordActions([
                Action::make('makeCurrent')
                    ->label('Rendi corrente')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (VideoSource $record): bool => ! $record->is_current)
                    ->requiresConfirmation()
                    ->action(function (VideoSource $record, Action $action): void {
                        Gate::authorize('update', $record->video);

                        $record->markAsCurrent();
                        $action->success();
                    })
                    ->successNotificationTitle('Versione impostata come corrente'),

                Action::make('deleteVersion')
                    ->label('Elimina versione')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    // La versione corrente non può essere eliminata.
                    ->visible(fn (VideoSource $record): bool => ! $record->is_current)
                    ->requiresConfirmation()
                    ->action(function (VideoSource $record, Action $action): void {
                        Gate::authorize('update', $record->video);

                        $record->delete();
                        $action->success();
                    })
                    ->successNotificationTitle('Versione eliminata'),
            ])
            ->defaultSort('version_date', 'desc')
            ->striped();
    }
}
