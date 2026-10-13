<?php

namespace App\Contracts;

interface FrontendSearchable
{
    public function toFrontendSearchResult(): array;

    public function getFrontendSearchType(): string;

    public function getFrontendSearchUrl(): string;

    public function getFrontendSearchTimestamp(): int;
}