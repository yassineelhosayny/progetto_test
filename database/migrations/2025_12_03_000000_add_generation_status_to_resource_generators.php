<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGenerationStatusToResourceGenerators extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('resource_generators', function (Blueprint $table) {
            if (!Schema::hasColumn('resource_generators', 'generation_status')) {
                $table->string('generation_status')->nullable()->default('pending')->after('is_public');
            }

            if (!Schema::hasColumn('resource_generators', 'generation_error')) {
                $table->text('generation_error')->nullable()->after('generation_status');
            }

            if (!Schema::hasColumn('resource_generators', 'generation_started_at')) {
                $table->timestamp('generation_started_at')->nullable()->after('generation_error');
            }

            if (!Schema::hasColumn('resource_generators', 'generation_finished_at')) {
                $table->timestamp('generation_finished_at')->nullable()->after('generation_started_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resource_generators', function (Blueprint $table) {
            $table->dropColumn([
                'generation_status',
                'generation_error',
                'generation_started_at',
                'generation_finished_at',
            ]);
        });
    }
}
