<?php

namespace App\Filament\Resources\Pages\Tables;

use Illuminate\Support\Facades\Log;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class PagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(true),

                TextColumn::make('title')
                    ->label('Titolo')
                    ->getStateUsing(fn($record) => $record->getTranslation('title', 'it') ?? '')
                    ->searchable(['title'])
                    ->sortable()
                    ->limit(50),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'danger' => 'draft',
                        'success' => 'published',
                        'info' => 'scheduled',
                    ])
                    ->icons([
                        'heroicon-o-pencil' => 'draft',
                        'heroicon-o-eye' => 'published',
                        'heroicon-o-clock' => 'scheduled',
                    ])
                    ->formatStateUsing(fn($state) => match ($state) {
                        'draft' => 'Bozza',
                        'published' => 'Pubblicata',
                        'scheduled' => 'Programmata',
                    })
                    ->sortable(),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->toggleable(true)
                    ->copyable()
                    ->limit(30),

                TextColumn::make('public_url')
                    ->label('URL')
                    ->getStateUsing(fn($record) => $record->public_url ?? null)
                    ->url(fn($record) => $record->public_url ?? null)
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-link')
                    ->limit(40)
                    ->copyable()
                    ->toggleable(),

                TextColumn::make('published_at')
                    ->label('Pubblicata')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->placeholder('Not scheduled')
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->label('Aggiornata')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Bozza',
                        'published' => 'Pubblicata',
                        'scheduled' => 'Programmata',
                    ]),
            ])
            ->recordActions([
                ViewAction::make()->icon('heroicon-o-eye'),
                EditAction::make()->icon('heroicon-o-pencil'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('updated_at', 'desc')
            ->striped()
            ->poll('30s'); // Auto-refresh per vedere cambiamenti di status
    }
}