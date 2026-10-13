<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Homepage
        Page::create([
            'title' => [
                'it' => 'Benvenuti nel Mondo della Natura',
                'en' => 'Welcome to the World of Nature',
            ],
            'description' => [
                'it' => 'Scopri la bellezza dei paesaggi naturali',
                'en' => 'Discover the beauty of natural landscapes',
            ],
            'slug' => 'home',
            'status' => 'published',
            'is_homepage' => true,
            'template' => 'homepage',
            'published_at' => now(),
        ]);

        // News Page
        Page::create([
            'title' => [
                'it' => 'News',
                'en' => 'News',
            ],
            'description' => [
                'it' => 'Tutte le ultime notizie dal mondo della natura',
                'en' => 'All the latest news from the world of nature',
            ],
            'slug' => 'news',
            'status' => 'published',
            'template' => 'news',
            'published_at' => now(),
        ]);

        // About Page
        Page::create([
            'title' => [
                'it' => 'Chi Siamo',
                'en' => 'About Us',
            ],
            'description' => [
                'it' => 'La nostra storia e passione per la natura',
                'en' => 'Our story and passion for nature',
            ],
            'slug' => 'about',
            'status' => 'published',
            'template' => 'default',
            'published_at' => now(),
        ]);

        // Contatti Page
        Page::create([
            'title' => [
                'it' => 'Contatti',
                'en' => 'Contact',
            ],
            'description' => [
                'it' => 'Come raggiungerci',
                'en' => 'How to reach us',
            ],
            'slug' => 'contatti',
            'status' => 'published',
            'template' => 'contact',
            'published_at' => now(),
        ]);
    }
}
