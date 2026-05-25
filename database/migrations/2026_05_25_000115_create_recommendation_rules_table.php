<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recommendation_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_type_id')
                  ->constrained('vehicle_types')
                  ->onDelete('cascade');
            $table->string('produce_type');
            $table->integer('max_weight_kg');
            $table->integer('max_distance_km');
            $table->boolean('temperature_sensitive')->default(false);
            $table->integer('priority')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recommendation_rules');
    }
};
