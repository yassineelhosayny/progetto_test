<?php

namespace App\Http\Livewire;

use App\Models\Page;
use App\Models\MenuItem;
use Biostate\FilamentMenuBuilder\FilamentMenuBuilderPlugin;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Schemas\Components\Grid;
use Filament\Support\Enums\Size;
use Illuminate\Support\Facades\Log;

class MenuBuilder extends \Biostate\FilamentMenuBuilder\Http\Livewire\MenuBuilder
{
    public function editAction(): Action
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

        /** @var class-string $menuItemResource */
        $menuItemResource = $plugin->getMenuItemResource();

        return Action::make('edit')
            ->tooltip(__('filament-menu-builder::menu-builder.edit_menu_item_tooltip'))
            ->size(Size::ExtraSmall)
            ->icon('heroicon-m-pencil-square')
            ->iconButton()
            ->fillForm(function (array $arguments) {
                $menuItemId = $arguments['menuItemId'] ?? throw new \InvalidArgumentException('menuItemId is required');
                $menuItem = MenuItem::find($menuItemId);

                if (! $menuItem) {
                    throw new \RuntimeException("Menu item with ID {$menuItemId} not found");
                }

                $data = $menuItem->toArray();

                // Prefer the stored `type_choice` virtual column if present so
                // values like 'placeholder' are preserved when opening the edit form.
                if (isset($data['type_choice']) && $data['type_choice'] !== null) {
                    if ($data['type_choice'] === 'page') {
                        $data['page_id'] = $data['menuable_id'] ?? null;
                    }
                } else {
                    if (isset($data['type']) && $data['type'] === 'model') {
                        $data['type_choice'] = 'page';
                        $data['page_id'] = $data['menuable_id'] ?? null;
                    } else {
                        $data['type_choice'] = $data['type'] ?? 'link';
                    }
                }

                $data['url'] = $data['url'] ?? null;

                return $data;
            })
            ->schema([
                Grid::make()
                    ->schema($menuItemResource::getFormSchema()),
            ])
            ->action(function (array $arguments, $data) {
                $menuItemId = $arguments['menuItemId'] ?? throw new \InvalidArgumentException('menuItemId is required');

                $menuItem = MenuItem::find($menuItemId);
                if (! $menuItem) {
                    throw new \RuntimeException("Menu item with ID {$menuItemId} not found");
                }

                // Map virtual fields to package fields and populate url when page selected
                if (isset($data['type_choice'])) {
                    if ($data['type_choice'] === 'page') {
                        $data['type'] = 'model';
                        $data['menuable_type'] = Page::class;
                        $data['menuable_id'] = $data['page_id'] ?? null;

                        if (! empty($data['menuable_id'])) {
                            $page = Page::find($data['menuable_id']);
                            if ($page) {
                                if (method_exists($page, 'getMenuLinkAttribute')) {
                                    $data['url'] = $page->menu_link;
                                } elseif (method_exists($page, 'getMenuLink')) {
                                    $data['url'] = $page->getMenuLink();
                                } elseif (isset($page->slug)) {
                                    $data['url'] = url($page->slug);
                                } elseif (isset($page->path)) {
                                    $data['url'] = url($page->path);
                                }
                            }
                        }
                    } elseif ($data['type_choice'] === 'link') {
                        $data['type'] = 'link';
                        $data['menuable_type'] = null;
                        $data['menuable_id'] = null;
                    } elseif ($data['type_choice'] === 'placeholder') {
                        $data['type'] = 'link';
                        $data['url'] = null;
                        $data['menuable_type'] = null;
                        $data['menuable_id'] = null;
                    }

                    unset($data['page_id']);
                }

                $temp = $data['route_parameters'] ?? [];
                $data['route_parameters'] = [];

                foreach ($temp as $key => $value) {
                    $data['route_parameters'][] = ['key' => $key, 'value' => $value];
                }
                Log::debug('MenuBuilder editAction data: ', $data);
                $menuItem->update($data);
            });
    }

    public function createSubItemAction(): Action
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

        $menuItemResource = $plugin->getMenuItemResource();

        return Action::make('createSubItem')
            ->tooltip(__('filament-menu-builder::menu-builder.create_sub_item_tooltip'))
            ->size(Size::ExtraSmall)
            ->icon('heroicon-m-plus')
            ->iconButton()
            ->schema([
                Grid::make()
                    ->schema($menuItemResource::getFormSchema()),
            ])
            ->action(function (array $arguments, $data) {
                $menuItemId = $arguments['menuItemId'] ?? throw new \InvalidArgumentException('menuItemId is required');

                $parent = MenuItem::find($menuItemId);
                if (! $parent) {
                    throw new \RuntimeException("Menu item with ID {$menuItemId} not found");
                }

                // Map virtual fields to package fields and populate url when page selected
                if (isset($data['type_choice'])) {
                    if ($data['type_choice'] === 'page') {
                        $data['type'] = 'model';
                        $data['menuable_type'] = Page::class;
                        $data['menuable_id'] = $data['page_id'] ?? null;

                        if (! empty($data['menuable_id'])) {
                            $page = Page::find($data['menuable_id']);
                            if ($page) {
                                if (method_exists($page, 'getMenuLinkAttribute')) {
                                    $data['url'] = $page->menu_link;
                                } elseif (method_exists($page, 'getMenuLink')) {
                                    $data['url'] = $page->getMenuLink();
                                } elseif (isset($page->slug)) {
                                    $data['url'] = url($page->slug);
                                } elseif (isset($page->path)) {
                                    $data['url'] = url($page->path);
                                }
                            }
                        }
                    } elseif ($data['type_choice'] === 'link') {
                        $data['type'] = 'link';
                        $data['menuable_type'] = null;
                        $data['menuable_id'] = null;
                    } elseif ($data['type_choice'] === 'placeholder') {
                        $data['type'] = 'link';
                        $data['url'] = null;
                        $data['menuable_type'] = null;
                        $data['menuable_id'] = null;
                    }

                    unset($data['page_id']);
                }

                $temp = $data['route_parameters'] ?? [];
                $data['route_parameters'] = [];

                foreach ($temp as $key => $value) {
                    $data['route_parameters'][] = ['key' => $key, 'value' => $value];
                }

                // Ensure is_active defaults to true when creating via builder
                if (! array_key_exists('is_active', $data)) {
                    $data['is_active'] = true;
                }

                $menuItem = MenuItem::create([
                    ...$data,
                    'menu_id' => $this->menuId,
                ]);

                $parent->appendNode($menuItem);
            });
    }
}
