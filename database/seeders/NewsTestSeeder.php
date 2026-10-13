<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;

class NewsTestSeeder extends Seeder
{
    public function run()
    {
        // Crea alcune news di test
        $newsData = [
            [
                'title' => [
                    'it' => 'Prima News di Tecnologia',
                    'en' => 'First Technology News'
                ],
                'excerpt' => [
                    'it' => 'Questo è un estratto della prima news di tecnologia',
                    'en' => 'This is an excerpt of the first technology news'
                ],
                'content' => [
                    'it' => '<p>Contenuto completo della prima news di tecnologia. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>',
                    'en' => '<p>Full content of the first technology news. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>'
                ],
                'slug' => 'prima-news-tecnologia',
                'status' => 'published',
                'featured' => true,
                'published_at' => now()->subDays(1),
            ],
            [
                'title' => [
                    'it' => 'News di Business',
                    'en' => 'Business News'
                ],
                'excerpt' => [
                    'it' => 'Estratto di una news di business',
                    'en' => 'Excerpt of a business news'
                ],
                'content' => [
                    'it' => '<p>Contenuto della news di business. Sed do eiusmod tempor incididunt ut labore.</p>',
                    'en' => '<p>Business news content. Sed do eiusmod tempor incididunt ut labore.</p>'
                ],
                'slug' => 'news-business',
                'status' => 'published',
                'featured' => false,
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => [
                    'it' => 'Altra News Tecnologia',
                    'en' => 'Another Technology News'
                ],
                'excerpt' => [
                    'it' => 'Un altro estratto di tecnologia',
                    'en' => 'Another technology excerpt'
                ],
                'content' => [
                    'it' => '<p>Altro contenuto di tecnologia. Ut enim ad minim veniam.</p>',
                    'en' => '<p>Another technology content. Ut enim ad minim veniam.</p>'
                ],
                'slug' => 'altra-news-tecnologia',
                'status' => 'published',
                'featured' => false,
                'published_at' => now()->subDays(3),
            ]
        ];

        foreach ($newsData as $data) {
            News::create($data);
        }

        echo "Creati " . count($newsData) . " articoli di test\n";
    }
}