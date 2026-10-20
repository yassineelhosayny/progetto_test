<?php

namespace App\Filament\Resources\VideoDemos\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class VideoDemoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Video demo')
                ->schema([
                    // Il cliente è facoltativo perché client_id è nullable nella migration.
                    Select::make('client_id')
                        ->label('Cliente')
                        ->relationship('client', 'client_name')
                        ->searchable()
                        ->preload()
                        ->nullable(),

                    TextInput::make('title')
                        ->label('Titolo')
                        ->required()
                        ->maxLength(255),

                    // Se vuoto, il Model genera lo slug dal titolo. Il Model garantisce anche l'unicità.
                    TextInput::make('slug')
                        ->label('Slug')
                        ->maxLength(255)
                        ->helperText('Facoltativo: viene normalizzato in formato URL. Se è vuoto, viene generato dal titolo.'),

                    // Il valore salvato nel database è il percorso relativo del file sul disco public.
                    FileUpload::make('logo')
                        ->label('Logo')
                        ->image()
                        ->disk('public')
                        ->directory('video-demos/logos')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->maxSize(2048)
                        ->previewable(),

                    Toggle::make('is_active')
                        ->label('Attiva')
                        ->default(true),

                    // live() aggiorna subito la visibilità del campo password.
                    Toggle::make('requires_password')
                        ->label('Richiede password')
                        ->default(false)
                        ->live(),

                    TextInput::make('password')
                        ->label('Password')
                        ->password()
                        // Comunica al browser che non è la password di accesso dell'amministratore.
                        ->autocomplete('new-password')
                        ->revealable()
                        ->minLength(8)
                        ->maxLength(255)
                        ->visible(fn (Get $get): bool => (bool) $get('requires_password'))
                        // In creazione è obbligatoria soltanto se la protezione è attiva.
                        ->required(
                            fn (Get $get, string $operation): bool =>
                                $operation === 'create' && (bool) $get('requires_password')
                        )
                        // In modifica, il campo vuoto non sostituisce la password esistente.
                        ->dehydrated(
                            fn (Get $get, ?string $state): bool =>
                                ! (bool) $get('requires_password') || filled($state)
                        )
                        ->helperText('In modifica, lascia vuoto per mantenere la password attuale.'),

                ])
                ->columns(2)
                ->columnSpanFull(),
        ]);
    }
}
