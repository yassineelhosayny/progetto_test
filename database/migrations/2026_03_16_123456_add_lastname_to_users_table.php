<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLastnameToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('users', 'lastname')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('lastname')->nullable()->after('name');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('users', 'lastname')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('lastname');
            });
        }
    }
}
