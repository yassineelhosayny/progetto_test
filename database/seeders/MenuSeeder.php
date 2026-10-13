<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\MenuItem;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menu principale
        $mainMenu = Menu::create([
            'name' => 'Menu Principale',
            'label' => 'Main Menu',
            'location' => 'main',
            'is_active' => true,
        ]);

        MenuItem::create([
            'menu_id' => $mainMenu->id,
            'label' => 'Chi Siamo',
            'url' => '/about',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        MenuItem::create([
            'menu_id' => $mainMenu->id,
            'label' => 'News',
            'url' => '/news',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        MenuItem::create([
            'menu_id' => $mainMenu->id,
            'label' => 'Contatti',
            'url' => '/contatti',
            'sort_order' => 4,
            'is_active' => true,
        ]);

        // Menu Footer
        $footerMenu = Menu::create([
            'name' => 'Menu Footer',
            'label' => 'Footer Menu',
            'location' => 'footer',
            'is_active' => true,
        ]);

        // Menu Items del footer
        MenuItem::create([
            'menu_id' => $footerMenu->id,
            'label' => 'Privacy Policy',
            'url' => '/privacy',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        MenuItem::create([
            'menu_id' => $footerMenu->id,
            'label' => 'Terms of Service',
            'url' => '/terms',
            'sort_order' => 2,
            'is_active' => true,
        ]);
    }
}
