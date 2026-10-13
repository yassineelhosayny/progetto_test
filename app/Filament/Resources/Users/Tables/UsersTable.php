<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Models\User;
use Dom\Text;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // Hide super_admins from non-super_admins
            // ->query(fn ($query) => dd($query))
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(true),
                TextColumn::make('name')
                    ->label('Nome')
                    ->sortable()
                    ->toggleable(true),
                TextColumn::make('lastname')
                    ->label('Cognome')
                    ->sortable()
                    ->toggleable(true),

                TextColumn::make('email')
                    ->label('E-mail')
                    ->sortable()
                    ->toggleable(true),
                TextColumn::make('roles.name')
                    ->label('Ruolo')
                    ->badge()
                    ->separator(', ')
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                // Prevent deleting self
                \Filament\Actions\DeleteAction::make()
                    ->visible(fn($record) => $record->id !== \Illuminate\Support\Facades\Auth::id()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->deselectRecordsAfterCompletion()
                        ->visible(fn($records) => !collect($records)->contains(fn($record) => $record->id === \Illuminate\Support\Facades\Auth::id())),
                ]),
            ]);
    }
}
