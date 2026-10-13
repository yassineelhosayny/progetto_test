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
        Schema::table('resource_generators', function (Blueprint $table) {
            // Slug per le route pubbliche (es: 'products', 'events')
            $table->string('slug')->nullable()->after('name');
            
            // Classe del model generato (es: 'App\Models\Product')
            $table->string('model_class')->nullable()->after('slug');
            
            // Flag per indicare se la risorsa è pubblica
            $table->boolean('is_public')->default(true)->after('model_class');
            
            // Indici per performance
            $table->unique('slug');
            $table->index('is_public');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resource_generators', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropIndex(['is_public']);
            $table->dropColumn(['slug', 'model_class', 'is_public']);
        });
    }
};
