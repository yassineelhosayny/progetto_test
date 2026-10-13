<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            
            // General Settings
            $table->string('site_name')->nullable();
            $table->text('site_description')->nullable();
            
            // Images
            $table->string('favicon')->nullable();
            $table->string('logo')->nullable();
            $table->string('og_image')->nullable();
            
            // Social Media Links
            $table->string('instagram_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('twitter_url')->nullable(); // X (Twitter)
            
            // Contact Information
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            
            // SEO Settings
            $table->json('meta_keywords')->nullable();
            $table->string('google_analytics_id')->nullable();
            $table->string('google_tag_manager_id')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
