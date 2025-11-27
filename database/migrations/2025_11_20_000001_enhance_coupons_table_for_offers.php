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
        Schema::table('coupons', function (Blueprint $table) {
            $table->enum('offer_type', ['common', 'product', 'category', 'brand', 'user', 'billing_amount'])->default('common')->after('code');
            $table->enum('user_segment', ['all', 'first_time_buyers', 'repeat_customers', 'minimum_purchase'])->nullable()->after('offer_type');
            $table->decimal('minimum_purchase_amount', 10, 2)->nullable()->after('user_segment');
            $table->integer('per_user_limit')->nullable()->after('usage_limit');
            $table->boolean('is_public')->default(true)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn([
                'offer_type',
                'user_segment',
                'minimum_purchase_amount',
                'per_user_limit',
                'is_public'
            ]);
        });
    }
};

