<?php

namespace App\Repositories;

use App\Models\Page;

class PageRepository
{
    public function findById($id)
    {
        return Page::findOrFail($id);
    }

    public function findBySlug($slug)
    {
        return Page::where('slug', $slug)->firstOrFail();
    }

    public function allPublished()
    {
        return Page::where('published', true)->get();
    }
}