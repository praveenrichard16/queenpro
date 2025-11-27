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
        Schema::table('drip_campaigns', function (Blueprint $table) {
            $table->string('timezone')->default(config('app.timezone', 'UTC'))->after('is_active');
            $table->time('send_window_start')->nullable()->after('timezone');
            $table->time('send_window_end')->nullable()->after('send_window_start');
            $table->unsignedTinyInteger('max_retries')->default(3)->after('send_window_end');
            $table->json('audience_filters')->nullable()->after('max_retries');
        });

        Schema::table('drip_campaign_steps', function (Blueprint $table) {
            $table->json('conditions')->nullable()->after('channel');
            $table->string('wait_until_event')->nullable()->after('conditions');
        });

        Schema::table('drip_campaign_recipients', function (Blueprint $table) {
            $table->unsignedTinyInteger('retry_count')->default(0)->after('next_send_at');
            $table->json('last_step_payload')->nullable()->after('retry_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drip_campaigns', function (Blueprint $table) {
            $table->dropColumn([
                'timezone',
                'send_window_start',
                'send_window_end',
                'max_retries',
                'audience_filters',
            ]);
        });

        Schema::table('drip_campaign_steps', function (Blueprint $table) {
            $table->dropColumn([
                'conditions',
                'wait_until_event',
            ]);
        });

        Schema::table('drip_campaign_recipients', function (Blueprint $table) {
            $table->dropColumn([
                'retry_count',
                'last_step_payload',
            ]);
        });
    }
};
