<?php

namespace App\Filament\Schemas\Components\blocks;

use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;

class ContactForm
{
    public static function make(): Block
    {
        return Block::make('contact_form')
            ->label('Contact Form')
            ->icon('heroicon-o-envelope')
            ->schema([
                TextInput::make('title')
                    ->label('Form Title')
                    ->maxLength(255)
                    ->placeholder('Get in Touch')
                    ->required(),
                
                Textarea::make('description')
                    ->label('Form Description')
                    ->rows(3)
                    ->maxLength(500)
                    ->placeholder('We would love to hear from you'),

                // Form Field Labels
                TextInput::make('labels.name')
                    ->label('Name Field Label')
                    ->maxLength(100)
                    ->placeholder('Nome')
                    ->default('Nome'),

                TextInput::make('labels.email')
                    ->label('Email Field Label')
                    ->maxLength(100)
                    ->placeholder('Email')
                    ->default('Email'),

                TextInput::make('labels.phone')
                    ->label('Phone Field Label')
                    ->maxLength(100)
                    ->placeholder('Telefono')
                    ->default('Telefono'),

                TextInput::make('labels.subject')
                    ->label('Subject Field Label')
                    ->maxLength(100)
                    ->placeholder('Oggetto')
                    ->default('Oggetto'),

                TextInput::make('labels.message')
                    ->label('Message Field Label')
                    ->maxLength(100)
                    ->placeholder('Messaggio')
                    ->default('Messaggio'),

                // Form Field Placeholders
                TextInput::make('placeholders.name')
                    ->label('Name Field Placeholder')
                    ->maxLength(100)
                    ->placeholder('Inserisci il tuo nome')
                    ->default('Inserisci il tuo nome'),

                TextInput::make('placeholders.email')
                    ->label('Email Field Placeholder')
                    ->maxLength(100)
                    ->placeholder('Inserisci la tua email')
                    ->default('Inserisci la tua email'),

                TextInput::make('placeholders.message')
                    ->label('Message Field Placeholder')
                    ->maxLength(100)
                    ->placeholder('Scrivi il tuo messaggio...')
                    ->default('Scrivi il tuo messaggio...'),

                // Field Visibility
                Toggle::make('fields.phone')
                    ->label('Show Phone Field')
                    ->default(false),

                Toggle::make('fields.subject')
                    ->label('Show Subject Field')
                    ->default(false),

                // Privacy Settings
                Toggle::make('privacy.enabled')
                    ->label('Require Privacy Consent')
                    ->default(true),

                TextInput::make('privacy.text')
                    ->label('Privacy Text')
                    ->maxLength(200)
                    ->placeholder('Accetto la')
                    ->default('Accetto la'),

                TextInput::make('privacy.link_text')
                    ->label('Privacy Link Text')
                    ->maxLength(100)
                    ->placeholder('Privacy Policy')
                    ->default('Privacy Policy'),

                TextInput::make('privacy.url')
                    ->label('Privacy Policy URL')
                    ->url()
                    ->placeholder('/privacy-policy'),

                // Button Texts
                TextInput::make('submit_text')
                    ->label('Submit Button Text')
                    ->maxLength(100)
                    ->placeholder('Invia Messaggio')
                    ->default('Invia Messaggio'),

                TextInput::make('submitting_text')
                    ->label('Submitting Text')
                    ->maxLength(100)
                    ->placeholder('Invio in corso...')
                    ->default('Invio in corso...'),

                // Styling
                Select::make('wrapper_classes')
                    ->label('Form Style')
                    ->options([
                        'bg-white p-8 rounded-lg shadow-lg' => 'White with Shadow',
                        'bg-gray-50 p-8 rounded-lg border' => 'Light Gray with Border',
                        'bg-transparent p-8' => 'Transparent',
                        'bg-blue-50 p-8 rounded-lg' => 'Light Blue Background',
                    ])
                    ->default('bg-white p-8 rounded-lg shadow-lg'),
            ]);
    }
}