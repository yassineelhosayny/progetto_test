<?php

namespace App\Traits;

use App\Services\TemplateManager;
use App\Helpers\LocaleHelper;

trait TemplateableResource
{
    protected static ?string $template = null;

    public static function getTemplateManager(): TemplateManager
    {
        return app(TemplateManager::class);
    }

    public static function getLocaleHelper(): LocaleHelper
    {
        return app(LocaleHelper::class);
    }

    public static function getTemplate(): ?string
    {
        return static::$template;
    }

    public static function setTemplate(?string $template): void
    {
        static::$template = $template;
    }

    // Puoi aggiungere qui altra logica per la gestione dei template
}
