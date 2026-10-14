<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('uploaded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->string('name');
            $table->string('type', 30);
            $table->string('provider', 30);
            $table->string('disk')->nullable(); //tipo di save 
            $table->text('path')->nullable();
            $table->text('public_url')->nullable();
            $table->string('original_filename')->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->string('upload_status', 30)->default('ready');
            $table->text('upload_error')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index('type');
            $table->index('provider');
            $table->index('upload_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_assets');
    }
};
