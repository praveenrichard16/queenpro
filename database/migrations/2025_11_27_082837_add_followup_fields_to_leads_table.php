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
        Schema::table('leads', function (Blueprint $table) {
            $table->date('next_followup_date')->nullable()->after('notes');
            $table->time('next_followup_time')->nullable()->after('next_followup_date');
            $table->integer('lead_score')->default(0)->after('next_followup_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['next_followup_date', 'next_followup_time', 'lead_score']);
        });
    }
};
