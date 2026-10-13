<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            if (!Schema::hasColumn('news', 'news_category_id')) {
                $table->unsignedBigInteger('news_category_id')->nullable()->after('id');
                $table->foreign('news_category_id')->references('id')->on('news_categories')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropForeign(['news_category_id']);
            $table->dropColumn('news_category_id');
        });
    }
};
