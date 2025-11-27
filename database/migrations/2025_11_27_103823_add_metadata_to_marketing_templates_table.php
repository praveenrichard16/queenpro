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
        Schema::table('marketing_templates', function (Blueprint $table) {
            $table->string('language')->nullable()->after('type');
            $table->string('category')->nullable()->after('language');
            $table->json('meta')->nullable()->after('variables');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_templates', function (Blueprint $table) {
            $table->dropColumn(['language', 'category', 'meta']);
        });
    }
};
