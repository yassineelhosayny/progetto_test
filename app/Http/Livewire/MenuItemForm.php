<?php

namespace App\Http\Livewire;

use App\Models\MenuItem;
use App\Models\Menu;
use Biostate\FilamentMenuBuilder\FilamentMenuBuilderPlugin;
use Filament\Facades\Filament;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

/**
 * @property int $menuId
 * @property array|null $data
 * @property \Filament\Schemas\Schema $form
 */
class MenuItemForm extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public int $menuId;

    public ?array $data = [];

    public function mount(int $menuId): void
    {
        $this->menuId = $menuId;
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        $panel = Filament::getCurrentPanel();
        if (! $panel) {
            throw new \RuntimeException('No active Filament panel');
        }

        /** @var FilamentMenuBuilderPlugin|null $plugin */
        $plugin = $panel->getPlugin('filament-menu-builder');
        if (! $plugin instanceof FilamentMenuBuilderPlugin) {
            throw new \RuntimeException('Filament Menu Builder plugin not registered');
        }

        /** @var class-string<\Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource> $menuItemResource */
        $menuItemResource = $plugin->getMenuItemResource();

        return $schema
            ->components([
                Section::make('Menu Item')
                    ->description('Create New Menu Item')
                    ->schema($menuItemResource::getFormSchemaArray())
                    ->footerActions([
                        \Filament\Actions\Action::make('submit')
                            ->label(__('filament-menu-builder::menu-builder.create_menu_item'))
                            ->submit('submit'),
                    ]),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $menuItem = array_merge($this->data ?? [], [
            'menu_id' => $this->menuId,
        ]);

        Log::debug('MenuItemForm submit payload', $menuItem);

        // Use App model to create so $fillable on App\Models\MenuItem is respected
        $menuItem = MenuItem::query()->create($menuItem);

        $this->form->fill();

        $this->dispatch('menu-item-created', menuId: $this->menuId, menuItemId: $menuItem->id);
    }

    public function render()
    {
        return view('filament-menu-builder::livewire.menu-item-form');
    }
}
