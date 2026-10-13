<?php

namespace App\Http\Controllers;

use App\Repositories\GeneratedResourceRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class GeneratedResourceController extends Controller
{
    public function __construct(
        private GeneratedResourceRepositoryInterface $repository
    ) {}

    /**
     * Mostra la lista dei record di una risorsa generata
     * URL: /products, /events, etc.
     */
    public function index(string $resource): View|Response
    {
        // Se esiste una pagina con questo slug, restituisci direttamente la risposta del PageController
        if (\App\Models\Page::where('slug', $resource)->where('status', 'published')->exists()) {
            return app(\App\Http\Controllers\PageController::class)->show($resource);
        }

        // Trova la risorsa generata
        $generator = $this->repository->findBySlug($resource);
        
        if (!$generator) {
            abort(404, "Resource '{$resource}' not found");
        }

        try {
            // Ottieni il model e tutti i record
            $modelInstance = $generator->getModelInstance();
            $records = $modelInstance->latest()->paginate(12);

            // Prova a caricare una view specifica, altrimenti fallback generica
            $viewName = "generated.{$resource}.index";
            
            if (!view()->exists($viewName)) {
                $viewName = 'generated.index'; // View generica
            }

            return view($viewName, [
                'resource' => $generator,
                'records' => $records,
                'resourceName' => $resource,
            ]);

        } catch (\Exception $e) {
            Log::error("Error loading resource index for {$resource}: " . $e->getMessage());
            abort(500, "Error loading resource");
        }
    }

    /**
     * Mostra un singolo record di una risorsa generata
     * URL: /products/my-product, /events/my-event, etc.
     */
    public function show(string $resource, string $slug): View|Response
    {
        // Trova la risorsa generata
        $generator = $this->repository->findBySlug($resource);
        
        if (!$generator) {
            abort(404, "Resource '{$resource}' not found");
        }

        // Trova il record specifico
        $record = $this->repository->findRecordBySlug($generator, $slug);
        
        if (!$record) {
            abort(404, "Record '{$slug}' not found in '{$resource}'");
        }

        try {
            // Controlla se il record ha un template specifico
            $template = $record->template ?? 'default';
            
            // Se ha template "blocks", usa il template blocks
            if ($template === 'blocks') {
                return view('templates.blocks', [
                    'page' => $record,
                    'resource' => $generator,
                    'resourceName' => $resource,
                ]);
            }
            
            // Prova a caricare una view specifica per il template
            $templateViewName = "templates.{$template}";
            if (view()->exists($templateViewName)) {
                return view($templateViewName, [
                    'page' => $record,
                    'record' => $record,
                    'resource' => $generator,
                    'resourceName' => $resource,
                ]);
            }
            
            // Prova a caricare una view specifica della risorsa, altrimenti fallback generica
            $viewName = "generated.{$resource}.show";
            
            if (!view()->exists($viewName)) {
                $viewName = 'generated.show'; // View generica
            }

            return view($viewName, [
                'resource' => $generator,
                'record' => $record,
                'resourceName' => $resource,
            ]);

        } catch (\Exception $e) {
            Log::error("Error loading record {$slug} for resource {$resource}: " . $e->getMessage());
            abort(500, "Error loading record");
        }
    }

    /**
     * API endpoint per ottenere tutte le risorse pubbliche
     */
    public function apiIndex(): \Illuminate\Http\JsonResponse
    {
        $resources = $this->repository->getAllPublic();
        
        return response()->json([
            'resources' => $resources->map(function ($resource) {
                return [
                    'name' => $resource->name,
                    'slug' => $resource->slug,
                    'url' => url('/' . $resource->slug),
                ];
            }),
        ]);
    }

    /**
     * Endpoint per invalidare cache (per admin)
     */
    public function invalidateCache(Request $request): \Illuminate\Http\JsonResponse
    {
        $slug = $request->get('slug');
        
        $this->repository->invalidateCache($slug);
        
        return response()->json([
            'message' => $slug 
                ? "Cache invalidated for resource: {$slug}" 
                : "All resource cache invalidated",
        ]);
    }
}