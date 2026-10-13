<?php

namespace App\Services;

use App\Filament\Schemas\Components\blocks\Hero;
use App\Filament\Schemas\Components\templates\homepage\Banner;
use App\Filament\Schemas\Components\templates\team\EmployeesRepeater;
use App\Filament\Schemas\Components\templates\homepage\Credits;
use App\Filament\Schemas\Components\blocks\TwoColumns;
use App\Filament\Schemas\Components\blocks\ContentBlock;
use App\Filament\Schemas\Components\blocks\CallToAction;
use App\Filament\Schemas\Components\blocks\CardsGrid;
use App\Filament\Schemas\Components\blocks\Gallery;
use App\Filament\Schemas\Components\blocks\Masonry;
use App\Filament\Schemas\Components\blocks\ContactForm;
use App\Filament\Schemas\Components\blocks\DearFlipBook;
use App\Filament\Schemas\Components\blocks\Editor;
use App\Filament\Schemas\Components\templates\homepage\HeroSlider;
use App\Filament\Schemas\Components\templates\homepage\Gallery as HomepageGallery;

class TemplateManager
{
    /**
     * Get all available templates with their configurations
     */
    public static function getAvailableTemplates(): array
    {
        return [
            'default' => [

                'label' => 'Default',
                'description' => 'Template base con layout standard',
            ],
            'homepage' => [
                'label' => 'Home Page',
                'description' => 'Template per homepage page con hero section',
            ],
            'news' => [
                'label' => 'News Gallery',
                'description' => 'Template automatico per gallery news - non richiede blocchi personalizzabili',
            ],
            'contacts' => [
                'label' => 'Contatti',
                'description' => 'Template per pagine di contatto',
            ],
            'team' => [
                'label' => 'Team',
                'description' => 'Template per pagine team aziendali',
            ]
        ];
    }

    /**
     * Get template options for select fields
     */
    public static function getTemplateOptions(): array
    {
        $templates = self::getAvailableTemplates();
        return collect($templates)->mapWithKeys(function ($config, $key) {
            return [$key => $config['label'] . ' - ' . $config['description']];
        })->toArray();
    }

    /**
     * Get configuration for a specific template
     */
    public static function getTemplateConfig(string $template): array
    {
        $templates = self::getAvailableTemplates();
        return $templates[$template] ?? $templates['default'];
    }

    /**
     * Get common blocks available for all templates
     */
    public static function getCommonBlocks(): array
    {
        return [
            Hero::make(),
            TwoColumns::make(),
            ContentBlock::make(),
            CallToAction::make(),
            CardsGrid::make(),
            Gallery::make(),
            Masonry::make(),
            ContactForm::make(),
            Editor::make(),
            DearFlipBook::make(),
        ];
    }

    /**
     * Get template-specific blocks
     */
    public static function getTemplateSpecificBlocks(string $template): array
    {
        switch ($template) {
            case 'homepage':
                return [
                    HeroSlider::make(),
                    Banner::make(),
                    Credits::make(),
                    HomepageGallery::make(),
                ];
            
            case 'news':
                return [
                    // Nessun blocco specifico per ora
                ];
            case 'team':
                return [
                    EmployeesRepeater::make(),
                ];
            case 'contacts':
                return [];
            case 'default':
            default:
                return [];
        }
    }

    /**
     * Get available Filament blocks for specific template
     */
    public static function getBlocksForTemplate(string $template): array
    {
        try {
            // Combina blocchi comuni + blocchi specifici del template
            $commonBlocks = self::getCommonBlocks();
            $specificBlocks = self::getTemplateSpecificBlocks($template);
            
            return array_merge($commonBlocks, $specificBlocks);
            
        } catch (\Exception $e) {
            logger('Error loading template blocks for ' . $template . ': ' . $e->getMessage());
            // In caso di errore, ritorna almeno i blocchi comuni
            return self::getCommonBlocks();
        }
    }

    /**
     * Get common component types available for all templates
     */
    public static function getCommonComponentTypes(): array
    {
        return [
            'hero',
            'two_columns',
            'content_block', 
            'call_to_action',
            'cards_grid',
            'gallery',
            'masonry',
            'contact_form',
            'dear_flip_book'
        ];
    }

    /**
     * Get template-specific component types
     */
    public static function getTemplateSpecificComponentTypes(string $template): array
    {
        switch ($template) {
            case 'homepage':
                return ['hero', 'hero_slider', 'banner', 'credits', 'gallery'];
            
            case 'news':
                return ['news_gallery', 'news_filter', 'news_grid'];
            
            case 'contacts':
                return [];

            case 'team':
                return ['employees_repeater'];
            case 'default':
            default:
                return ['hero', 'hero_slider', 'custom', 'menu', 'dear_flip_book'];
        }
    }

    /**
     * Get available component types for a template (for frontend rendering)
     */
    public static function getAvailableComponentTypes(string $template): array
    {
        $commonTypes = self::getCommonComponentTypes();
        $specificTypes = self::getTemplateSpecificComponentTypes($template);
        
        return array_merge($commonTypes, $specificTypes);
    }

    /**
     * Check if template has configurable blocks from admin
     */
    public static function hasConfigurableBlocks(string $template): bool
    {
        // Ora tutti i template hanno almeno i blocchi comuni
        // Solo alcuni template non supportano affatto i blocchi
        $nonConfigurableTemplates = []; // Nessun template escluso per ora
        
        return !in_array($template, $nonConfigurableTemplates);
    }

    /**
     * Check if a template exists
     */
    public static function templateExists(string $template): bool
    {
        return array_key_exists($template, self::getAvailableTemplates());
    }

    /**
     * Check if template has specific component type
     */
    public static function hasComponentType(string $template, string $componentType): bool
    {
        return in_array($componentType, self::getAvailableComponentTypes($template));
    }
}
