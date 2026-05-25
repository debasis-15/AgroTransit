<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('min_capacity_kg');
            $table->integer('max_capacity_kg');
            $table->boolean('refrigerated')->default(false);
            $table->decimal('base_rate_per_km', 8, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_types');
    }
};
