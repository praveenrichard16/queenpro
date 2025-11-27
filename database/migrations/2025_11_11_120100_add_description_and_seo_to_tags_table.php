<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tags', function (Blueprint $table): void {
            if (!Schema::hasColumn('tags', 'description')) {
                $table->text('description')->nullable()->after('slug');
            }

            if (!Schema::hasColumn('tags', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('description');
            }

            if (!Schema::hasColumn('tags', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tags', function (Blueprint $table): void {
            if (Schema::hasColumn('tags', 'meta_description')) {
                $table->dropColumn('meta_description');
            }

            if (Schema::hasColumn('tags', 'meta_title')) {
                $table->dropColumn('meta_title');
            }

            if (Schema::hasColumn('tags', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};

