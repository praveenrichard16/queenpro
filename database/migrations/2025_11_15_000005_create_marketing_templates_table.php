<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketing_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // email, whatsapp, push_notification
            $table->string('subject')->nullable();
            $table->text('content');
            $table->json('variables')->nullable(); // Available template variables
            $table->string('whatsapp_template_id')->nullable(); // Meta WhatsApp template ID
            $table->string('whatsapp_template_status')->nullable(); // pending, approved, rejected
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['type', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing_templates');
    }
};

