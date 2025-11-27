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
        Schema::table('home_sliders', function (Blueprint $table) {
            $table->boolean('show_title')->default(true)->after('button_link');
            $table->boolean('show_description')->default(true)->after('show_title');
            $table->boolean('show_button')->default(true)->after('show_description');
            $table->string('button_size', 20)->nullable()->after('show_button');
            $table->string('button_color', 20)->nullable()->after('button_size');
            $table->string('title_position', 20)->nullable()->after('button_color');
            $table->string('description_position', 20)->nullable()->after('title_position');
            $table->string('button_position', 20)->nullable()->after('description_position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_sliders', function (Blueprint $table) {
            $table->dropColumn([
                'show_title',
                'show_description',
                'show_button',
                'button_size',
                'button_color',
                'title_position',
                'description_position',
                'button_position',
            ]);
        });
    }
};
