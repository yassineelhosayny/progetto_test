<?php

namespace App\Filament\Resources\FormTemplates\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use App\Models\FormTemplate;

class FormTemplatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label')
                    ->label('Template Name')
                    ->searchable()
                    ->sortable(),
                
                BadgeColumn::make('category')
                    ->label('Category')
                    ->colors([
                        'primary' => 'page',
                        'success' => 'news', 
                        'warning' => 'form',
                        'danger' => 'modal',
                        'info' => 'widget',
                        'secondary' => 'custom',
                    ])
                    ->formatStateUsing(fn ($state) => FormTemplate::getCategories()[$state] ?? ucfirst($state)),
                
                TextColumn::make('version')
                    ->label('Version')
                    ->badge()
                    ->color('gray'),
                
                BooleanColumn::make('is_active')
                    ->label('Active')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),
                
                BooleanColumn::make('is_system')
                    ->label('System')
                    ->trueIcon('heroicon-o-lock-closed')
                    ->falseIcon('heroicon-o-lock-open')
                    ->trueColor('warning')
                    ->falseColor('gray'),
                
                TextColumn::make('fields_count')
                    ->label('Fields')
                    ->getStateUsing(fn ($record) => count($record->schema['fields'] ?? []))
                    ->badge()
                    ->color('info'),
                
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->options(FormTemplate::getCategories()),
                
                TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('All templates')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),
                
                TernaryFilter::make('is_system')
                    ->label('Type')
                    ->placeholder('All types')
                    ->trueLabel('System templates')
                    ->falseLabel('Custom templates'),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Preview'),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->action(function ($records) {
                            // Prevent deletion of system templates
                            $records->reject(fn ($record) => $record->is_system)->each->delete();
                        }),
                ]),
            ])
            ->defaultSort('updated_at', 'desc');
    }
}
