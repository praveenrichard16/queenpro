<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table): void {
            if (!Schema::hasColumn('categories', 'image_path')) {
                $table->string('image_path')->nullable()->after('slug');
            }

            if (!Schema::hasColumn('categories', 'icon_path')) {
                $table->string('icon_path')->nullable()->after('image_path');
            }

            if (!Schema::hasColumn('categories', 'image_alt_text')) {
                $table->string('image_alt_text')->nullable()->after('icon_path');
            }

            if (!Schema::hasColumn('categories', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('image_alt_text');
            }

            if (!Schema::hasColumn('categories', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table): void {
            if (Schema::hasColumn('categories', 'meta_description')) {
                $table->dropColumn('meta_description');
            }

            if (Schema::hasColumn('categories', 'meta_title')) {
                $table->dropColumn('meta_title');
            }

            if (Schema::hasColumn('categories', 'image_alt_text')) {
                $table->dropColumn('image_alt_text');
            }

            if (Schema::hasColumn('categories', 'icon_path')) {
                $table->dropColumn('icon_path');
            }

            if (Schema::hasColumn('categories', 'image_path')) {
                $table->dropColumn('image_path');
            }
        });
    }
};

