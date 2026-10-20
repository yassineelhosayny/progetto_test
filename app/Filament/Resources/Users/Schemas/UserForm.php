<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255),

                TextInput::make('lastname')
                    ->label('Cognome')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('E-mail')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    // Controlla l'unicità prima dell'INSERT; in modifica ignora l'utente corrente.
                    ->unique(User::class, 'email', ignoreRecord: true)
                    ->validationMessages([
                        'unique' => 'Questa email esiste già.',
                    ]),

                TextInput::make('password')
                    ->password()
                    ->label('Password')
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->minLength(8)
                    ->maxLength(255)
                    // In modifica, se resta vuota, non invia il campo e conserva la password esistente.
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->helperText('Lascia vuoto per non modificare la password')
                    ->revealable(),

                // Ogni utente amministrativo deve avere uno solo dei due ruoli previsti.
                Select::make('roles')
                    ->label('Ruolo')
                    ->multiple()
                    ->maxItems(1)
                    ->required()
                    ->relationship(
                        name: 'roles',
                        titleAttribute: 'name',
                        modifyQueryUsing: function (Builder $query): Builder {
                            $authUser = request()->user();

                            if (! $authUser instanceof User || ! $authUser->hasRole('super_admin')) {
                                return $query->where('name', 'admin');
                            }

                            return $query->whereIn('name', ['super_admin', 'admin']);
                        }
                    )
                    ->preload()
                    ->getOptionLabelFromRecordUsing(
                        fn (Role $role): string => Str::headline($role->name)
                    ),
            ]);
    }
}
