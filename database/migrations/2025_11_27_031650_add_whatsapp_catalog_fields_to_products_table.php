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
        Schema::table('products', function (Blueprint $table) {
            $table->string('whatsapp_product_id')->nullable()->after('id');
            $table->boolean('is_synced_to_whatsapp')->default(false)->after('whatsapp_product_id');
            $table->timestamp('whatsapp_synced_at')->nullable()->after('is_synced_to_whatsapp');
            $table->text('whatsapp_sync_error')->nullable()->after('whatsapp_synced_at');
            
            $table->index('whatsapp_product_id');
            $table->index('is_synced_to_whatsapp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['whatsapp_product_id']);
            $table->dropIndex(['is_synced_to_whatsapp']);
            $table->dropColumn([
                'whatsapp_product_id',
                'is_synced_to_whatsapp',
                'whatsapp_synced_at',
                'whatsapp_sync_error',
            ]);
        });
    }
};
