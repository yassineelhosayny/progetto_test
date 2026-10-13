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
        Schema::table('pages', function (Blueprint $table) {
            // SEO fields (translatable)
            $table->json('meta_title')->nullable();
            $table->json('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();
            
            // Status and publishing
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            
            // Performance optimization
            $table->index(['status', 'published_at']);
            $table->index('slug'); // optimize slug lookups
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropIndex(['status', 'published_at']);
            $table->dropIndex(['slug']);
            $table->dropColumn([
                'meta_title',
                'meta_description', 
                'meta_keywords',
                'status',
                'published_at'
            ]);
        });
    }
};
