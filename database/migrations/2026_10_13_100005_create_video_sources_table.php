<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('video_sources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_id')
                ->constrained('videos')
                ->cascadeOnDelete();
            $table->string('provider', 30);
            $table->string('label')->nullable();
            $table->string('external_id')->nullable();
            $table->string('provider_library_id')->nullable();
            $table->text('source_url')->nullable();
            $table->text('thumbnail_url')->nullable();
            $table->string('upload_method', 30)->default('manual');
            $table->string('upload_status', 30)->default('ready');
            $table->text('upload_error')->nullable();
            $table->timestamp('version_date')->nullable();
            $table->boolean('is_current')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index('provider');
            $table->index('upload_status');
            $table->index(['video_id', 'is_current']);
            $table->index(['video_id', 'is_active', 'sort_order']);
            $table->index(['provider', 'provider_library_id', 'external_id']);
            $table->unique(['video_id','provider','external_id'],'video_source_provider_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_sources');
    }
};
