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
        // Add 'show_on_homepage' and 'allow_subscribe' columns
        Schema::table('plans', function (Blueprint $table) {
            $table->boolean('allow_subscribe')->nullable(false)->default(false);
            $table->boolean('show_on_homepage')->nullable(false)->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop 'show_on_homepage' and 'allow_subscribe' columns
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('allow_subscribe');
            $table->dropColumn('show_on_homepage');
        });
    }
};
