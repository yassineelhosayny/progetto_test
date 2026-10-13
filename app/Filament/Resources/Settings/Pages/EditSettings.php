<?php

namespace App\Filament\Resources\Settings\Pages;

use App\Filament\Resources\Settings\SettingsResource;
use App\Models\Settings;
use Filament\Resources\Pages\EditRecord;

class EditSettings extends EditRecord
{
    protected static string $resource = SettingsResource::class;

    protected static ?string $title = 'Impostazioni';

    protected static ?string $navigationLabel = 'Impostazioni';

    public function mount(int|string $record = null): void
    {
        // Per Settings singleton, ottieni sempre il primo record o creane uno
        $settings = Settings::first();
        
        if (!$settings) {
            $settings = Settings::create([
                'site_name' => config('app.name', 'Laravel'),
                'site_description' => 'Welcome to our website',
            ]);
        }

        parent::mount($settings->id);
    }

    protected function getHeaderActions(): array
    {
        return [
            // Nessuna azione - le Settings sono singleton
        ];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
