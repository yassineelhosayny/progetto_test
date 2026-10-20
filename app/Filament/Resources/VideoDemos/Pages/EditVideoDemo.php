<?php

namespace App\Filament\Resources\VideoDemos\Pages;

use App\Filament\Resources\VideoDemos\VideoDemoResource;
use App\Models\VideoDemo;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVideoDemo extends EditRecord
{
    protected static string $resource = VideoDemoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Se esistono categorie, la cancellazione a cascata sarebbe distruttiva.
            DeleteAction::make()
                ->visible(fn (VideoDemo $record): bool => ! $record->categories()->exists()),
        ];
    }
}
