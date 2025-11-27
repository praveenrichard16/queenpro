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
        Schema::table('lead_followups', function (Blueprint $table) {
            $table->string('reminder_status')->default('pending')->after('status');
            $table->timestamp('reminder_sent_at')->nullable()->after('followup_time');
            $table->string('reminder_channel')->nullable()->after('reminder_sent_at');
            $table->unsignedTinyInteger('reminder_attempts')->default(0)->after('reminder_channel');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lead_followups', function (Blueprint $table) {
            $table->dropColumn([
                'reminder_status',
                'reminder_sent_at',
                'reminder_channel',
                'reminder_attempts',
            ]);
        });
    }
};
