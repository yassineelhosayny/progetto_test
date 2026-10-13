<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('pages')) {
            return;
        }

        Schema::table('pages', function (Blueprint $table) {
            if (! Schema::hasColumn('pages', 'description')) {
                $table->text('description')->nullable()->after('title');
            }

            if (! Schema::hasColumn('pages', 'slug')) {
                // nullable per sicurezza; unique crea indice unico
                $table->string('slug')->nullable()->unique()->after('description');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('pages')) {
            return;
        }

        Schema::table('pages', function (Blueprint $table) {
            if (Schema::hasColumn('pages', 'slug')) {
                // rimuove indice unico su slug (dropUnique accetta array di colonne)
                $table->dropUnique(['slug']);
                $table->dropColumn('slug');
            }

            if (Schema::hasColumn('pages', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};