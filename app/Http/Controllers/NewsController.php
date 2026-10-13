<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $locale = app()->getLocale();
        
        // Query base per le news pubblicate
        $query = News::published()->with('category')->orderBy('publication_date', 'desc');
        
        // Filtro per anno
        if ($request->has('year')) {
            $query->whereYear('publication_date', $request->get('year'));
        }
        
        // Filtro per categoria se specificato
        if ($request->has('category')) {
            $categorySlug = $request->get('category');
            $category = NewsCategory::where('slug', $categorySlug)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }
        
        // Filtro per news in evidenza
        if ($request->has('featured')) {
            $query->where('featured', true);
        }
        
        $news = $query->paginate(12);

        // Carica gli anni disponibili per il filtro
        $years = News::selectRaw('YEAR(publication_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return Inertia::render('News', compact('news', 'years'));
    }

    public function category($categoryId)
    {
        $locale = app()->getLocale();

        $category = NewsCategory::findOrFail($categoryId);

        // Carica le news di questa categoria
        $news = News::published()
                   ->where('news_category_id', $categoryId)
                   ->orderBy('published_at', 'desc')
                   ->paginate(12);

        // Carica tutte le categorie per il filtro
        $categories = NewsCategory::where('is_active', true)
                                 ->orderBy('name')
                                 ->get();

        return view('news.category', compact('news', 'categories', 'category'));
    }

    public function show($slug)
    {
        $news = News::where('slug', $slug)
                   ->published()
                   ->firstOrFail();

        // News correlate (stessa categoria, escludendo quella corrente)
        $relatedNews = News::published()
                          ->where('id', '!=', $news->id)
                          ->when($news->category_id, fn($q) => $q->where('category_id', $news->category_id))
                          ->limit(3)
                          ->get();

        // Carica la categoria se esiste
        $category = $news->category_id ? NewsCategory::find($news->category_id) : null;

        return Inertia::render('SingleNews', compact('news', 'relatedNews', 'category'));
    }

    public function filterByYear(Request $request, $year)
    {
        $locale = app()->getLocale();
        $query = News::published()->with('category')->orderBy('published_at', 'desc');
        $query->whereYear('publication_date', $year);
        if ($request->has('featured')) {
            $query->where('featured', true);
        }
        $news = $query->paginate(12);
        // Carica gli anni disponibili per il filtro
        $years = News::selectRaw('YEAR(publication_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
        return view('news.index', compact('news', 'years', 'year'));
    }
}