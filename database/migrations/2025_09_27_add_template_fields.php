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
        // Aggiungi il campo template_data alla tabella pages
        Schema::table('pages', function (Blueprint $table) {
            $table->json('template_data')->nullable()->after('blocks');
            $table->unsignedBigInteger('form_template_id')->nullable()->after('template_data');
            $table->foreign('form_template_id')->references('id')->on('form_templates');
        });

        // Aggiungi il campo template_data alla tabella news (se esiste)
        if (Schema::hasTable('news')) {
            Schema::table('news', function (Blueprint $table) {
                $table->json('template_data')->nullable()->after('content');
                $table->unsignedBigInteger('news_template_id')->nullable()->after('template_data');
                $table->foreign('news_template_id')->references('id')->on('form_templates');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropForeign(['form_template_id']);
            $table->dropColumn(['template_data', 'form_template_id']);
        });

        if (Schema::hasTable('news')) {
            Schema::table('news', function (Blueprint $table) {
                $table->dropForeign(['news_template_id']);
                $table->dropColumn(['template_data', 'news_template_id']);
            });
        }
    }
};
