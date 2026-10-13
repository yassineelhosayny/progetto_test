<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            
            // Campi base multilingua (JSON)
            $table->json('title');
            $table->json('description')->nullable();
            $table->json('excerpt')->nullable(); // Riassunto per listing
            $table->json('content')->nullable(); // Contenuto completo
            
            // Slug per URL
            $table->string('slug')->unique();
            
            // SEO fields (JSON per multilingua)
            $table->json('meta_title')->nullable();
            $table->json('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();
            
            // Publishing workflow
            $table->enum('status', ['draft', 'published', 'scheduled'])->default('draft');
            $table->timestamp('published_at')->nullable();
            
            // News specific fields
            $table->boolean('featured')->default(false); // News in evidenza
            $table->string('category')->nullable(); // Categoria news
            $table->json('tags')->nullable(); // Tags per filtri
            
            // Content blocks (come Pages)
            $table->json('blocks')->nullable();
            
            // Legacy compatibility
            $table->boolean('published')->default(false);
            
            $table->timestamps();
            
            // Indexes per performance
            $table->index('status');
            $table->index('published_at');
            $table->index('featured');
            $table->index('category');
            $table->index(['status', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};