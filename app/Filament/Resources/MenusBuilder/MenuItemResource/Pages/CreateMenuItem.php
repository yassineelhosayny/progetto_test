<?php

namespace App\Filament\Resources\MenusBuilder\MenuItemResource\Pages;

use App\Models\Page;
use Biostate\FilamentMenuBuilder\Filament\Resources\MenuItemResource\Pages\CreateMenuItem as BaseCreate;

class CreateMenuItem extends BaseCreate
{
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Translate the app-level `type_choice` into the package-expected `type` enum
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
            // remove the virtual field so model casting does not see it
            unset($data['page_id']);
        }

        return parent::mutateFormDataBeforeCreate($data);
    }
}
