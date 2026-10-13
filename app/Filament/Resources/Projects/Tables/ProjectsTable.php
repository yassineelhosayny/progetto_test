<?php

namespace App\Filament\Resources\Projects\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('featured_image')
                    ->label('Immagine')
                    ->disk('public')
                    ->square()
                    ->toggleable(),

                TextColumn::make('title')
                    ->label('Titolo')
                    ->getStateUsing(fn ($record) => $record->getTranslation('title', 'it', false) ?? '')
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
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'draft' => 'Bozza',
                        'published' => 'Pubblicato',
                        'scheduled' => 'Programmato',
                        default => $state,
                    })
                    ->sortable(),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->copyable()
                    ->limit(30)
                    ->toggleable(),

                TextColumn::make('public_url')
                    ->label('URL')
                    ->getStateUsing(fn ($record) => $record->public_url)
                    ->url(fn ($record) => $record->public_url)
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-link')
                    ->limit(40)
                    ->copyable()
                    ->toggleable(),

                TextColumn::make('sort_order')
                    ->label('Ordine')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('published_at')
                    ->label('Pubblicato')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->placeholder('Non impostato')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Bozza',
                        'published' => 'Pubblicato',
                        'scheduled' => 'Programmato',
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
            ->defaultSort('sort_order')
            ->striped();
    }
}
