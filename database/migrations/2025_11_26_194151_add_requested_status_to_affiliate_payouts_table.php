<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the enum to include 'requested' status
        DB::statement("ALTER TABLE affiliate_payouts MODIFY COLUMN status ENUM('requested', 'pending', 'processing', 'paid', 'failed') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE affiliate_payouts MODIFY COLUMN status ENUM('pending', 'processing', 'paid', 'failed') DEFAULT 'pending'");
    }
};
