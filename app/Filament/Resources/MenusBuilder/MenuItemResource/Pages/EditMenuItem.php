<?php

namespace App\Filament\Resources\MenusBuilder\MenuItemResource\Pages;

use App\Models\Page;
use Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\EditMenuItem as BaseEdit;

class EditMenuItem extends BaseEdit
{
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // If a virtual `type_choice` value is stored, prefer it so choices
        // like 'placeholder' remain selected when opening the edit form.
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

        return parent::mutateFormDataBeforeFill($data);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['type_choice'])) {
            if ($data['type_choice'] === 'page') {
                $data['type'] = 'model';
                $data['menuable_type'] = Page::class;
                $data['menuable_id'] = $data['page_id'] ?? null;
                // Populate url from the Page model when possible
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

        return parent::mutateFormDataBeforeSave($data);
    }
}
