<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('session_id')->nullable();
            $table->string('customer_email')->nullable();
            $table->json('cart_data');
            $table->timestamp('last_activity')->nullable();
            $table->boolean('is_abandoned')->default(false);
            $table->timestamps();
            
            $table->index(['user_id', 'is_abandoned']);
            $table->index(['session_id', 'is_abandoned']);
            $table->index('customer_email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_sessions');
    }
};

