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
        Schema::create('form_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('label');
            $table->text('description')->nullable();
            $table->string('category')->default('custom');
            $table->json('schema'); // JSON form configuration
            $table->json('metadata')->nullable(); // Additional metadata (version, author, etc.)
            $table->boolean('is_active')->default(true);
            $table->boolean('is_system')->default(false); // System templates can't be deleted
            $table->string('version', 20)->default('1.0.0');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['category', 'is_active']);
            $table->index('is_system');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_templates');
    }
};
