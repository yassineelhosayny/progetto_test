<?php

namespace App\Http\Controllers;

use App\Services\FrontendSearchService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function __invoke(Request $request, FrontendSearchService $searchService)
    {
        $q = trim((string) $request->string('q'));

        $results = $searchService->search($q);

        $locale = app()->getLocale();

        // return view('search.index', [
        //     'q' => $q,
        //     'results' => $results,
        // ]);

        return Inertia::render('Search/Index', [
            'filters' => [
                'q' => $q,
            ],
            'results' => $results,
            'locale' => $locale,
        ]);
    }
}