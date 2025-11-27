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
        Schema::create('drip_campaign_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('drip_campaign_id')->constrained()->onDelete('cascade');
            $table->string('recipient_type'); // enquiry, lead, customer
            $table->unsignedBigInteger('recipient_id'); // ID of enquiry, lead, or customer
            $table->integer('current_step')->default(1);
            $table->enum('status', ['pending', 'in_progress', 'completed', 'paused', 'cancelled'])->default('pending');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('last_sent_at')->nullable();
            $table->timestamp('next_send_at')->nullable();
            $table->timestamps();
            
            $table->index(['drip_campaign_id', 'status']);
            $table->index(['recipient_type', 'recipient_id']);
            $table->index('next_send_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drip_campaign_recipients');
    }
};
