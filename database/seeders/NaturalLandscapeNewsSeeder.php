<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\News;
use App\Models\NewsCategory;

class NaturalLandscapeNewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creiamo prima le categorie
        $conservazione = NewsCategory::create([
            'name' => [
                'it' => 'Conservazione',
                'en' => 'Conservation',
            ],
            'description' => [
                'it' => 'Progetti e iniziative per la conservazione della natura',
                'en' => 'Projects and initiatives for nature conservation',
            ],
            'slug' => 'conservazione',
        ]);

        $paesaggi = NewsCategory::create([
            'name' => [
                'it' => 'Paesaggi',
                'en' => 'Landscapes',
            ],
            'description' => [
                'it' => 'Scoperte e storie sui nostri paesaggi naturali',
                'en' => 'Discoveries and stories about our natural landscapes',
            ],
            'slug' => 'paesaggi',
        ]);

        $educazione = NewsCategory::create([
            'name' => [
                'it' => 'Educazione Ambientale',
                'en' => 'Environmental Education',
            ],
            'description' => [
                'it' => 'Programmi educativi e sensibilizzazione ambientale',
                'en' => 'Educational programs and environmental awareness',
            ],
            'slug' => 'educazione-ambientale',
        ]);

        // News 1
        News::create([
            'title' => [
                'it' => 'Nuovo Progetto di Riforestazione nelle Alpi',
                'en' => 'New Reforestation Project in the Alps',
            ],
            'content' => [
                'it' => '<p>È iniziato il nostro ambizioso progetto di riforestazione nelle Alpi piemontesi, che prevede la piantumazione di oltre 10.000 alberi autoctoni nei prossimi due anni.</p><p>Il progetto, realizzato in collaborazione con le comunità locali e il Parco Nazionale, mira a ripristinare gli ecosistemi forestali danneggiati dai cambiamenti climatici e dalle attività umane.</p><p>Le specie selezionate includono abete rosso, larice, faggio e acero montano, tutte perfettamente adattate al clima alpino e fondamentali per la biodiversità locale.</p>',
                'en' => '<p>Our ambitious reforestation project in the Piedmont Alps has begun, which provides for the planting of over 10,000 native trees in the next two years.</p><p>The project, carried out in collaboration with local communities and the National Park, aims to restore forest ecosystems damaged by climate change and human activities.</p><p>The selected species include Norway spruce, larch, beech and mountain maple, all perfectly adapted to the alpine climate and fundamental for local biodiversity.</p>',
            ],
            'excerpt' => [
                'it' => 'Un nuovo progetto per piantare 10.000 alberi autoctoni nelle Alpi piemontesi',
                'en' => 'A new project to plant 10,000 native trees in the Piedmont Alps',
            ],
            'slug' => 'riforestazione-alpi-piemonte',
            'featured_image' => '/images/news/reforestation-alps.jpg',
            'published_at' => now()->subDays(5),
            'status' => 'published',
            'meta_title' => [
                'it' => 'Riforestazione Alpi - Progetto Conservazione Natura',
                'en' => 'Alps Reforestation - Nature Conservation Project',
            ],
            'meta_description' => [
                'it' => 'Nuovo progetto di riforestazione nelle Alpi piemontesi per ripristinare ecosistemi forestali',
                'en' => 'New reforestation project in Piedmont Alps to restore forest ecosystems',
            ],
            'news_category_id' => $conservazione->id,
        ]);

        // News 2
        News::create([
            'title' => [
                'it' => 'Scoperto Nuovo Sentiero Panoramico nel Parco',
                'en' => 'New Panoramic Trail Discovered in the Park',
            ],
            'content' => [
                'it' => '<p>I nostri esploratori hanno individuato un nuovo sentiero panoramico che offre viste mozzafiato sulla valle sottostante e sui picchi circostanti.</p><p>Il percorso, lungo circa 8 chilometri, è adatto a escursionisti di livello intermedio e attraversa diversi ecosistemi, dalle foreste di conifere ai prati alpini.</p><p>Durante la primavera, il sentiero si trasforma in un caleidoscopio di colori grazie alla fioritura di rododendri, stelle alpine e altre specie endemiche.</p><p>Il nuovo tracciato sarà ufficialmente aperto al pubblico il prossimo mese, dopo i lavori di messa in sicurezza e segnaletica.</p>',
                'en' => '<p>Our explorers have identified a new panoramic trail that offers breathtaking views of the valley below and surrounding peaks.</p><p>The route, about 8 kilometers long, is suitable for intermediate level hikers and crosses different ecosystems, from coniferous forests to alpine meadows.</p><p>During spring, the trail transforms into a kaleidoscope of colors thanks to the flowering of rhododendrons, edelweiss and other endemic species.</p><p>The new trail will be officially opened to the public next month, after safety and signage work.</p>',
            ],
            'excerpt' => [
                'it' => 'Un nuovo sentiero di 8 km con viste panoramiche sui picchi alpini',
                'en' => 'A new 8 km trail with panoramic views of the alpine peaks',
            ],
            'slug' => 'nuovo-sentiero-panoramico-parco',
            'featured_image' => '/images/news/panoramic-trail.jpg',
            'published_at' => now()->subDays(10),
            'status' => 'published',
            'meta_title' => [
                'it' => 'Nuovo Sentiero Panoramico - Escursioni Natura',
                'en' => 'New Panoramic Trail - Nature Hiking',
            ],
            'meta_description' => [
                'it' => 'Scoperto nuovo sentiero panoramico di 8 km con viste mozzafiato sui picchi alpini',
                'en' => 'New 8km panoramic trail discovered with breathtaking views of alpine peaks',
            ],
            'news_category_id' => $paesaggi->id,
        ]);

        // News 3
        News::create([
            'title' => [
                'it' => 'Workshop di Fotografia Naturalistica per Studenti',
                'en' => 'Nature Photography Workshop for Students',
            ],
            'content' => [
                'it' => '<p>Il mese prossimo organizzeremo un workshop di fotografia naturalistica dedicato agli studenti delle scuole superiori, per avvicinarli alla bellezza della natura attraverso l\'arte fotografica.</p><p>Durante il corso di tre giorni, i partecipanti impareranno le tecniche base della fotografia naturalistica, dall\'uso della luce naturale alla composizione, fino alla post-produzione digitale.</p><p>Il workshop si terrà direttamente sul campo, nei luoghi più suggestivi del nostro territorio, permettendo ai giovani di sperimentare diverse condizioni di ripresa e soggetti.</p><p>Gli istruttori sono fotografi professionisti specializzati in natura e paesaggio, che condivideranno la loro esperienza e passione per questo genere fotografico.</p>',
                'en' => '<p>Next month we will organize a nature photography workshop dedicated to high school students, to introduce them to the beauty of nature through photographic art.</p><p>During the three-day course, participants will learn the basic techniques of nature photography, from the use of natural light to composition, to digital post-production.</p><p>The workshop will be held directly in the field, in the most evocative places of our territory, allowing young people to experiment with different shooting conditions and subjects.</p><p>The instructors are professional photographers specialized in nature and landscape, who will share their experience and passion for this photographic genre.</p>',
            ],
            'excerpt' => [
                'it' => 'Workshop di 3 giorni per insegnare la fotografia naturalistica agli studenti',
                'en' => '3-day workshop to teach nature photography to students',
            ],
            'slug' => 'workshop-fotografia-naturalistica-studenti',
            'featured_image' => '/images/news/photography-workshop.jpg',
            'published_at' => now()->subDays(15),
            'status' => 'published',
            'meta_title' => [
                'it' => 'Workshop Fotografia Naturalistica - Educazione Ambientale',
                'en' => 'Nature Photography Workshop - Environmental Education',
            ],
            'meta_description' => [
                'it' => 'Workshop di fotografia naturalistica per studenti, corso di 3 giorni sul campo',
                'en' => 'Nature photography workshop for students, 3-day field course',
            ],
            'news_category_id' => $educazione->id,
        ]);
    }
}
