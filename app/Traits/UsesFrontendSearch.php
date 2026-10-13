<?php

namespace App\Traits;

use Laravel\Scout\Attributes\SearchUsingFullText;
use Laravel\Scout\Searchable;

trait UsesFrontendSearch
{
    use Searchable;

    #[SearchUsingFullText(['title', 'description'])]
    public function toSearchableArray(): array
    {
        return [
            'title' => strip_tags((string) ($this->title ?? '')),
            // 'abstract' => strip_tags((string) ($this->abstract ?? '')),
            'description' => strip_tags((string) ($this->description ?? '')),
        ];
    }

    public function toFrontendSearchResult(): array
    {
        return [
            'type' => $this->getFrontendSearchType(),
            'title' => (string) ($this->title ?? ''),
            'description' => (string) ($this->description ?? ''),
            // 'abstract' => (string) ($this->abstract ?? ''),
            'url' => $this->getFrontendSearchUrl(),
            'date' => $this->getFrontendSearchTimestamp(),
            'model' => $this,
        ];
    }

    public function getFrontendSearchTimestamp(): int
    {
        foreach (['published_at', 'starts_at', 'created_at'] as $field) {
            $value = $this->{$field} ?? null;

            if ($value) {
                return $value->timestamp;
            }
        }

        return 0;
    }
}