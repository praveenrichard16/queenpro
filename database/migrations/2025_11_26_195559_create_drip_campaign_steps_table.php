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
        Schema::create('drip_campaign_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('drip_campaign_id')->constrained()->onDelete('cascade');
            $table->integer('step_number');
            $table->integer('delay_hours')->default(0); // Delay from previous step
            $table->foreignId('template_id')->nullable()->constrained('marketing_templates')->onDelete('set null');
            $table->enum('channel', ['email', 'whatsapp'])->default('email');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['drip_campaign_id', 'step_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drip_campaign_steps');
    }
};
