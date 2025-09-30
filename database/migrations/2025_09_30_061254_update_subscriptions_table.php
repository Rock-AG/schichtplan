<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('nickname', 255)
                ->after('id')
                ->nullable(false)
                ->default('');
            $table->string('name', 255)
                ->nullable()
                ->default(null)
                ->change();
        });

        DB::statement('UPDATE `subscriptions` SET `nickname` = `name`');
        DB::statement('UPDATE `subscriptions` SET `name` = NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('UPDATE `subscriptions` SET `name` = `nickname`');

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('nickname');
        });
    }
};
