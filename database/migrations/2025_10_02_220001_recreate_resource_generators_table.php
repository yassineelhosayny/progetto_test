<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('resource_generators');
        Schema::create('resource_generators', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('has_categories')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resource_generators');
    }
};
