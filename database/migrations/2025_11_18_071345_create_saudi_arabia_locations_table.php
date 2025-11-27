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
        Schema::create('saudi_arabia_locations', function (Blueprint $table) {
            $table->id();
            $table->string('region_id')->nullable();
            $table->string('region_name_ar')->nullable();
            $table->string('region_name_en')->nullable();
            $table->string('city_id')->nullable();
            $table->string('city_name_ar')->nullable();
            $table->string('city_name_en');
            $table->string('district_id')->nullable();
            $table->string('district_name_ar')->nullable();
            $table->string('district_name_en')->nullable();
            $table->string('postal_code', 10)->nullable()->index();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['region_name_en', 'city_name_en']);
            $table->index('postal_code');
            $table->index('city_name_en');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saudi_arabia_locations');
    }
};
