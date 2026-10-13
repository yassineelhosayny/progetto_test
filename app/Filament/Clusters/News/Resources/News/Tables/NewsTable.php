<?php

namespace App\Filament\Clusters\News\Resources\News\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Illuminate\Database\Eloquent\Builder;

class NewsTable
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
                    ->getStateUsing(fn($record) => $record->title ?? 'Nessun titolo')
                    ->searchable()
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
                        'published' => 'Pubblicato',
                        'scheduled' => 'Programmato',
                    })
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label('Categoria')
                    ->searchable()
                    ->sortable()
                    ->toggleable(true)
                    ->badge()
                    ->color(function (string $state): string {
                        switch ($state) {
                            case 'technology':
                                return 'info';
                            case 'business':
                                return 'success';
                            case 'sports':
                                return 'warning';
                            case 'entertainment':
                                return 'danger';
                            default:
                                return 'gray';
                        }
                    }),

                TextColumn::make('published_at')
                    ->label('Pubblicato')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->placeholder('Not scheduled')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Bozza',
                        'published' => 'Pubblicato',
                        'scheduled' => 'Programmato',
                    ]),

                SelectFilter::make('category')
                    ->label('Categoria')
                    ->options([
                        'general' => 'General',
                        'technology' => 'Technology',
                        'business' => 'Business',
                        'sports' => 'Sports',
                        'entertainment' => 'Entertainment',
                    ]),

                Filter::make('featured')
                    ->query(fn(Builder $query): Builder => $query->where('featured', true))
                    ->label('In evidenza'),
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
            ->poll('30s'); // Auto-refresh ogni 30 secondi
    }
}
