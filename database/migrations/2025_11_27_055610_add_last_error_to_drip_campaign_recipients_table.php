<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('drip_campaign_recipients', function (Blueprint $table) {
            $table->text('last_error')->nullable()->after('next_send_at');
        });
        
        // Update status enum to include 'failed'
        DB::statement("ALTER TABLE drip_campaign_recipients MODIFY COLUMN status ENUM('pending', 'in_progress', 'completed', 'paused', 'cancelled', 'failed') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drip_campaign_recipients', function (Blueprint $table) {
            $table->dropColumn('last_error');
        });
        
        // Revert status enum
        DB::statement("ALTER TABLE drip_campaign_recipients MODIFY COLUMN status ENUM('pending', 'in_progress', 'completed', 'paused', 'cancelled') DEFAULT 'pending'");
    }
};
