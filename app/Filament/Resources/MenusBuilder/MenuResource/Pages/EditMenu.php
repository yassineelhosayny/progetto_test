<?php

namespace App\Filament\Resources\MenusBuilder\MenuResource\Pages;

use Biostate\FilamentMenuBuilder\Filament\Resources\MenuResource\Pages\EditMenu as BaseEdit;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;

class EditMenu extends BaseEdit
{
    protected function getActions(): array
    {
        $resource = static::getResource();

        return [
            Action::make(__('filament-menu-builder::menu-builder.configure_menu'))
                ->url(fn ($record): string => $resource::getUrl('build', ['record' => $record]))
                ->icon('heroicon-o-bars-3'),
            Action::make(__('filament-menu-builder::menu-builder.regerate_slug'))
                ->action(function ($record) {
                    $record->generateSlug();
                    $record->save();
                })
                ->after(fn () => $this->fillForm())
                ->requiresConfirmation()
                ->icon('heroicon-o-arrow-path'),
            DeleteAction::make(),
        ];
    }
}
