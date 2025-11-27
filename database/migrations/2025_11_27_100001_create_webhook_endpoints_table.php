<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('webhook_endpoints', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('url');
            $table->string('secret')->nullable();
            $table->string('source')->nullable()->comment('e.g., lead, customer, enquiry, etc.');
            $table->json('events')->nullable()->comment('Array of event types to listen to');
            $table->boolean('is_active')->default(true);
            $table->integer('timeout')->default(30)->comment('Timeout in seconds');
            $table->integer('max_attempts')->default(3)->comment('Maximum retry attempts');
            $table->timestamps();
            $table->softDeletes();

            $table->index('is_active');
            $table->index('source');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webhook_endpoints');
    }
};

