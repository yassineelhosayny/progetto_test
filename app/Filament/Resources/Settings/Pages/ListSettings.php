<?php

namespace App\Filament\Resources\Settings\Pages;

use App\Filament\Resources\Settings\SettingsResource;
use App\Models\Settings;
use Filament\Resources\Pages\ListRecords;

class ListSettings extends ListRecords
{
    protected static string $resource = SettingsResource::class;

    public function mount(): void
    {
        // Reindirizza direttamente alla pagina di edit
        $settings = Settings::first();
        if (!$settings) {
            $settings = Settings::create([
                'site_name' => config('app.name', 'Laravel'),
                'site_description' => 'Welcome to our website',
            ]);
        }
        
        $this->redirect($this->getResource()::getUrl('edit', ['record' => $settings->id]));
    }

    protected function getHeaderActions(): array
    {
        return [
            // Nessuna azione perché reindirizza
        ];
    }
}
