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
        Schema::table('news', function (Blueprint $table) {
            $table->string('featured_image')->nullable()->after('slug');
            $table->unsignedBigInteger('category_id')->nullable()->after('featured_image');
            
            // Aggiungiamo anche l'indice per la foreign key se necessario
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropIndex(['category_id']);
            $table->dropColumn(['featured_image', 'category_id']);
        });
    }
};
