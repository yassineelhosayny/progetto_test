<?php

return '<?php

use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Database\\Schema\\Blueprint;
use Illuminate\\Support\\Facades\\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(\'{{TABLE_NAME}}\', function (Blueprint $table) {
            $table->id();
            $table->json(\'name\')->nullable();
            $table->string(\'slug\')->unique();
            $table->json(\'description\')->nullable();
            $table->json(\'content\')->nullable();
            $table->json(\'blocks\')->nullable();
            $table->boolean(\'is_active\')->default(true);
            $table->integer(\'sort_order\')->default(0);
            $table->json(\'meta_title\')->nullable();
            $table->json(\'meta_description\')->nullable();
            $table->json(\'meta_keywords\')->nullable();
            $table->string(\'template\')->default(\'default\');
            $table->json(\'template_components\')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(\'{{TABLE_NAME}}\');
    }
};
';