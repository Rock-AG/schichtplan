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
        // Add contact person columns to shifts table
        Schema::table('shifts', function(Blueprint $table) {
            $table->string('contact_name')->nullable(true)->default(null);
            $table->string('contact_email')->nullable(true)->default(null);
            $table->string('contact_phone')->nullable(true)->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove contact person columns from shifts table
        Schema::table('shifts', function(Blueprint $table) {
            $table->dropColumn('contact_name');
            $table->dropColumn('contact_email');
            $table->dropColumn('contact_phone');
        });
    }
};
