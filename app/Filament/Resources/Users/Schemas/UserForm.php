<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use App\Models\Page;
use App\Models\News;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome')
                    ->required(),
                TextInput::make('lastname')
                    ->label('Cognome')
                    ->required(),
                TextInput::make('email')
                    ->email()
                    ->label('E-mail')
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->label('Password')
                    ->helperText('Lascia vuoto per non modificare la password')
                    ->dehydrateStateUsing(function ($state, $record) {
                        // Solo se viene inserita una nuova password
                        if (!empty($state)) {
                            return bcrypt($state);
                        }
                        // In modifica, se vuoto, non aggiornare il campo
                        return $record ? $record->password : null;
                    })
                    
                    ->required(fn($context) => $context === 'create'),

                // Ruoli (Shield)
                Select::make('roles')
                    ->label('Ruoli')
                    ->multiple()
                    ->maxItems(1)
                    ->options(
                        Role::all()->pluck('name')->mapWithKeys(fn($name) => [
                            $name => Str::headline($name)
                        ])->toArray()
                    )
                    ->afterStateHydrated(function ($component, $state, $record) {
                        if ($record) {
                            $component->state($record->roles->pluck('name')->toArray());
                        }
                    })
                    ->dehydrateStateUsing(function ($state, $record) {
                        if ($record) {
                            $record->syncRoles($state ?? []);
                        }
                        return $state;
                    }),

                // Permessi generali (Shield)
                Select::make('permissions')
                    ->label('Permessi')
                    ->multiple()
                    ->options(
                        Permission::all()->pluck('name', 'name')->toArray()
                    )
                    ->afterStateHydrated(function ($component, $state, $record) {
                        if ($record) {
                            // Solo i permessi globali (non granulari)
                            $component->state(
                                $record->permissions->pluck('name')->filter(function ($p) {
                                    return !str_starts_with($p, 'view page ') && !str_starts_with($p, 'update page ') && !str_starts_with($p, 'view news ') && !str_starts_with($p, 'update news ');
                                })->values()->toArray()
                            );
                        }
                    })
                    ->dehydrateStateUsing(function ($state, $record) {
                        if ($record) {
                            // Prendi tutti i permessi globali
                            $allGlobalPerms = Permission::all()->pluck('name')->filter(function ($p) {
                                return !str_starts_with($p, 'view page ') && !str_starts_with($p, 'update page ') && !str_starts_with($p, 'view news ') && !str_starts_with($p, 'update news ');
                            })->values()->toArray();
                            $record->revokePermissionTo($allGlobalPerms);
                            $record->givePermissionTo($state ?? []);
                        }
                        return $state;
                    }),

                // Permessi granulari: pagine
                Select::make('page_permissions')
                    ->label('Pagine visibili e modificabili (permessi granulari)')
                    ->multiple()
                    ->options(
                        Page::all()->mapWithKeys(fn($page) => [
                            'view page ' . $page->id => $page->title
                        ])->toArray()
                    )
                    ->afterStateHydrated(function ($component, $state, $record) {
                        if ($record) {
                            $component->state(
                                $record->permissions->pluck('name')->filter(fn($p) => str_starts_with($p, 'view page '))->values()->toArray()
                            );
                        }
                    })
                    ->dehydrateStateUsing(function ($state, $record) {
                        // Assegna/rimuovi permessi granulari view+update sulle pagine
                        if ($record) {
                            $allPagePerms = Page::all()->flatMap(fn($page) => [
                                'view page ' . $page->id,
                                'update page ' . $page->id,
                            ])->toArray();
                            $toGive = collect($state ?? [])->flatMap(function ($perm) {
                                $id = (int) str_replace('view page ', '', $perm);
                                return ['view page ' . $id, 'update page ' . $id];
                            })->toArray();
                            $record->revokePermissionTo($allPagePerms);
                            $record->givePermissionTo($toGive);
                        }
                        return $state;
                    }),

                // Permessi granulari: news
                Select::make('news_permissions')
                    ->label('News visibili e modificabili (permessi granulari)')
                    ->multiple()
                    ->options(
                        News::all()->mapWithKeys(fn($news) => [
                            'view news ' . $news->id => $news->title
                        ])->toArray()
                    )
                    ->afterStateHydrated(function ($component, $state, $record) {
                        if ($record) {
                            $component->state(
                                $record->permissions->pluck('name')->filter(fn($p) => str_starts_with($p, 'view news '))->values()->toArray()
                            );
                        }
                    })
                    ->dehydrateStateUsing(function ($state, $record) {
                        // Assegna/rimuovi permessi granulari view+update sulle news
                        if ($record) {
                            $allNewsPerms = News::all()->flatMap(fn($news) => [
                                'view news ' . $news->id,
                                'update news ' . $news->id,
                            ])->toArray();
                            $toGive = collect($state ?? [])->flatMap(function ($perm) {
                                $id = (int) str_replace('view news ', '', $perm);
                                return ['view news ' . $id, 'update news ' . $id];
                            })->toArray();
                            $record->revokePermissionTo($allNewsPerms);
                            $record->givePermissionTo($toGive);
                        }
                        return $state;
                    }),
            ]);
    }
}
