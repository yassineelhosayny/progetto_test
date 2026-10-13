<?php

namespace App\Services;

use App\Contracts\FrontendSearchable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class FrontendSearchService
{
    public function search(string $term, ?int $perPage = null): LengthAwarePaginator
    {
        $term = trim($term);
        $perPage = $perPage ?? (int) config('frontend-search.per_page', 12);

        if ($term === '') {
            return $this->paginate(collect(), $perPage);
        }

        $results = collect();

        foreach (config('frontend-search.models', []) as $modelClass) {
            if (! is_subclass_of($modelClass, FrontendSearchable::class)) {
                continue;
            }

            $model = new $modelClass();

            $items = $modelClass::search($term)
                ->query(function (Builder $query) use ($model) {
                    if (method_exists($model, 'scopeFrontendSearchVisible')) {
                        $query->frontendSearchVisible();
                    }
                })
                ->get()
                ->map(fn (FrontendSearchable $item) => $item->toFrontendSearchResult());

            $results = $results->merge($items);
        }

        $results = $results
            ->sort(function (array $a, array $b) {
                return $b['date'] <=> $a['date'];
            })
            ->values();

        return $this->paginate($results, $perPage);
    }

    protected function paginate(Collection $items, int $perPage): LengthAwarePaginator
    {
        $page = LengthAwarePaginator::resolveCurrentPage();
        $total = $items->count();

        $slice = $items
            ->slice(($page - 1) * $perPage, $perPage)
            ->values();

        return new LengthAwarePaginator(
            $slice,
            $total,
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );
    }
}