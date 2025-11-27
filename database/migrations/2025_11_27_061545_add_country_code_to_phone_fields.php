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
        // Add country_code to users table
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'phone_country_code')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('phone_country_code', 5)->nullable()->after('phone');
            });
        }

        // Add country_code to leads table
        if (Schema::hasTable('leads') && !Schema::hasColumn('leads', 'phone_country_code')) {
            Schema::table('leads', function (Blueprint $table) {
                $table->string('phone_country_code', 5)->nullable()->after('phone');
            });
        }

        // Add country_code to orders table
        if (Schema::hasTable('orders') && !Schema::hasColumn('orders', 'customer_phone_country_code')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('customer_phone_country_code', 5)->nullable()->after('customer_phone');
            });
        }

        // Add country_code to customer_addresses table
        if (Schema::hasTable('customer_addresses') && !Schema::hasColumn('customer_addresses', 'contact_phone_country_code')) {
            Schema::table('customer_addresses', function (Blueprint $table) {
                $table->string('contact_phone_country_code', 5)->nullable()->after('contact_phone');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'phone_country_code')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('phone_country_code');
            });
        }

        if (Schema::hasTable('leads') && Schema::hasColumn('leads', 'phone_country_code')) {
            Schema::table('leads', function (Blueprint $table) {
                $table->dropColumn('phone_country_code');
            });
        }

        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'customer_phone_country_code')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('customer_phone_country_code');
            });
        }

        if (Schema::hasTable('customer_addresses') && Schema::hasColumn('customer_addresses', 'contact_phone_country_code')) {
            Schema::table('customer_addresses', function (Blueprint $table) {
                $table->dropColumn('contact_phone_country_code');
            });
        }
    }
};
