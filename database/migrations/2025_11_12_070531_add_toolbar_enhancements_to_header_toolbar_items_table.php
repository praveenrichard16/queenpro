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
        Schema::table('header_toolbar_items', function (Blueprint $table) {
            $table->string('link', 2048)->nullable()->after('text_color');
            $table->string('toolbar_height', 50)->nullable()->after('link');
            $table->string('font_size', 50)->nullable()->after('toolbar_height');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('header_toolbar_items', function (Blueprint $table) {
            $table->dropColumn(['link', 'toolbar_height', 'font_size']);
        });
    }
};
