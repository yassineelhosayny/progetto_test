<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Settings extends Model
{
    use HasFactory;

    protected $fillable = [
        // General
        'site_name',
        'site_description',
        'informations',
        
        // Images
        'favicon',
        'logo',
        'og_image',
        
        // Social Media Links
        'instagram_url',
        'facebook_url',
        'linkedin_url',
        'youtube_url',
        'twitter_url', // X (Twitter)
        
        // Contact Info
        'email',
        'phone',
        'address',
        'contacts',
        'opening_hours',

        // SEO
        'meta_keywords',
        'google_analytics_id',
        'google_tag_manager_id',

        // FILES
        'pdf_documents',
    ];

    protected $casts = [
        'meta_keywords' => 'array',
        'pdf_documents' => 'array',
    ];

    /**
     * Ottieni le impostazioni come singleton
     */
    public static function getSettings()
    {
        return static::first() ?? new static();
    }

    /**
     * Aggiorna o crea le impostazioni
     */
    public static function updateSettings(array $data)
    {
        $settings = static::first();
        
        if ($settings) {
            $settings->update($data);
        } else {
            $settings = static::create($data);
        }
        
        return $settings;
    }

    /**
     * Ottieni URL social media
     */
    public function getSocialLinks()
    {
        return [
            'instagram' => $this->instagram_url,
            'facebook' => $this->facebook_url,
            'linkedin' => $this->linkedin_url,
            'youtube' => $this->youtube_url,
            'twitter' => $this->twitter_url,
        ];
    }

    /**
     * Ottieni l'URL del logo
     */
    public function getLogoUrl()
    {
        if ($this->logo && \Storage::disk('public')->exists($this->logo)) {
            return asset('storage/' . $this->logo);
        }
        return null;
    }

    /**
     * Ottieni l'URL del logo statico per utilizzo globale
     */
    public static function getLogoUrlStatic()
    {
        $settings = static::getSettings();
        return $settings->getLogoUrl();
    }

    public function getFaviconUrl()
    {
        if ($this->favicon && \Storage::disk('public')->exists($this->favicon)) {
            return asset('storage/' . $this->favicon);
        }
        return null;
    }

    public static function getFaviconUrlStatic()
    {
        $settings = static::getSettings();
        return $settings->getFaviconUrl();
    }
}
