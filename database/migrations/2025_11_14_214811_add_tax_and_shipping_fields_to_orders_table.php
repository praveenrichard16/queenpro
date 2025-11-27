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
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('subtotal', 10, 2)->nullable()->after('billing_address');
            $table->decimal('tax_amount', 10, 2)->default(0)->after('subtotal');
            $table->unsignedBigInteger('shipping_method_id')->nullable()->after('tax_amount');
            $table->decimal('shipping_amount', 10, 2)->default(0)->after('shipping_method_id');
        });
        
        // Add foreign key constraint only if shipping_methods table exists
        if (Schema::hasTable('shipping_methods')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->foreign('shipping_method_id')->references('id')->on('shipping_methods')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['shipping_method_id']);
            $table->dropColumn(['subtotal', 'tax_amount', 'shipping_method_id', 'shipping_amount']);
        });
    }
};
