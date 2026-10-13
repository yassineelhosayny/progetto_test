<?php

namespace App\Http\Controllers;

use App\Repositories\PageRepository;
use App\Models\Page;
use App\Models\ResourceGenerator;
use App\Services\TemplateManager;
use Inertia\Inertia;

class PageController extends Controller
{
    // public function homepage()
    // {
    //     $page = Page::where('is_homepage', true)
    //                ->where('status', 'published')
    //                ->first();

    //     if (!$page) {
    //         // Se non c'è homepage, mostra una pagina di default o 404
    //         abort(404, 'Homepage not found');
    //     }

    //     return $this->renderPage($page);
    // }

    /**
     * Homepage con Inertia/Vue (esempio PoC)
     */
    public function homepage()
    {
        $page = Page::where('is_homepage', true)
                   ->where('status', 'published')
                   ->first();

        if (!$page) {
            abort(404, 'Homepage not found');
        }

        $template = $page->template ?? 'default';
        
        // Verifica che il template esista
        if (!TemplateManager::templateExists($template)) {
            $template = 'default';
        }

        // Prepara i dati specifici per il template
        $templateData = $this->getTemplateData($page, $template);

        return Inertia::render('Homepage', [
            'page' => $page,
        ] + $templateData);
    }

    public function show($slug)
    {
        // DEBUG: Log per capire se passa da qui
        // \Log::info('PageController::show() chiamato con slug: ' . $slug);

        $page = Page::where('slug', $slug)
                   ->where('status', 'published')
                   ->firstOrFail();

        if (!$page) {
            abort(404, 'Page not found');
        }

        $template = $page->template ?? 'default';
        
        // Verifica che il template esista
        if (!TemplateManager::templateExists($template)) {
            $template = 'default';
        }

        // Prepara i dati specifici per il template
        $templateData = $this->getTemplateData($page, $template);

        return Inertia::render(ucfirst($template), [
            'page' => $page,
        ] + $templateData);
    }

    /**
     * Render page based on its template
     */
    protected function renderPage(Page $page)
    {
        $template = $page->template ?? 'default';
        
        // Verifica che il template esista
        if (!TemplateManager::templateExists($template)) {
            $template = 'default';
        }

        // Prova a caricare il template specifico, altrimenti fallback al default
        $templateView = "pages.templates.{$template}";
        
        if (!view()->exists($templateView)) {
            $templateView = 'pages.templates.default';
        }

        // Prepara i dati specifici per il template
        $templateData = $this->getTemplateData($page, $template);

        return view($templateView, [
            'page' => $page,
            'template' => $template,
            'blocks' => $page->blocks ?? [],
        ] + $templateData);
    }

    /**
     * Get specific data for each template type
     */
    protected function getTemplateData(Page $page, string $template): array
    {
        // Non caricare dati pesanti durante request admin per evitare memory issues
        if (request()->is('admin/*') || request()->is('filament/*')) {
            return [];
        }

        switch ($template) {
            case 'news':
                $locale = app()->getLocale();
                $news = \App\Models\News::published()
                    ->orderBy('published_at', 'desc')
                    ->paginate(12)
                    ->through(function($item) use ($locale) {
                        $item->title = $item->getTranslation('title', $locale) ?? $item->getTranslation('title', 'it');
                        $item->excerpt = $item->getTranslation('excerpt', $locale) ?? $item->getTranslation('excerpt', 'it');
                        $item->content = $item->getTranslation('content', $locale) ?? $item->getTranslation('content', 'it');
                        return $item;
                    });
                return [
                    'news' => $news,
                ];
            case 'homepage':
                // Potresti aggiungere dati specifici per l'homepage
                return [];
            case 'team':
                // Potresti aggiungere dati specifici per il team
                return [];
            default:
                return [];
        }
    }
}