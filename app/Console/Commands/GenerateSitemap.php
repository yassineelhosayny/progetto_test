<?php

namespace App\Console\Commands;

use App\Models\Page;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Console\Command;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'app:generate-sitemap';
    protected $description = 'Genera sitemap.xml multilingua';

    public function handle(): int
    {
        $sitemap = Sitemap::create();

        $locales = array_keys(LaravelLocalization::getSupportedLocales());

        foreach ($locales as $locale) {
            // Homepage localizzata
            $sitemap->add(
                Url::create(url($locale))
                    ->setPriority(1.0)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            );

            // Listing news
            $sitemap->add(
                Url::create(url($locale . '/news'))
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            );

            // Pagine CMS pubblicate
            Page::query()
                ->where('status', 'published')
                ->get()
                ->each(function (Page $page) use ($sitemap, $locale) {
                    $path = $page->is_homepage
                        ? $locale
                        : $locale . '/' . ltrim($page->slug, '/');

                    $sitemap->add(
                        Url::create(url($path))
                            ->setLastModificationDate($page->updated_at ?? now())
                            ->setPriority($page->is_homepage ? 1.0 : 0.7)
                            ->setChangeFrequency(
                                $page->is_homepage
                                    ? Url::CHANGE_FREQUENCY_WEEKLY
                                    : Url::CHANGE_FREQUENCY_MONTHLY
                            )
                    );
                });

            // Articoli news pubblicati
            // News::query()
            //     ->where('status', 'published')
            //     ->get()
            //     ->each(function (News $news) use ($sitemap, $locale) {
            //         $sitemap->add(
            //             Url::create(url($locale . '/news/' . $news->slug))
            //                 ->setLastModificationDate($news->updated_at ?? $news->published_at ?? now())
            //                 ->setPriority(0.7)
            //                 ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            //         );
            //     });
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('sitemap.xml generata correttamente.');

        return self::SUCCESS;
    }
}