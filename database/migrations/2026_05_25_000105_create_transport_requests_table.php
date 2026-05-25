<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transport_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')
                  ->constrained('farmer_profiles')
                  ->onDelete('cascade');
            $table->string('crop_name');
            $table->integer('weight_kg');
            $table->string('pickup');
            $table->string('destination');
            $table->date('pickup_date')->nullable();
            $table->string('transport_type')->nullable();
            $table->text('special_requirements')->nullable();
            $table->integer('distance_km')->default(0);
            $table->boolean('temperature_sensitive')->default(false);
            $table->enum('priority', ['normal', 'emergency'])->default('normal');
            $table->decimal('estimated_cost', 10, 2)->nullable();
            $table->enum('status', ['pending', 'matched', 'assigned', 'in_transit', 'delivered', 'cancelled', 'pooled'])->default('pending');
            $table->foreignId('vehicle_type_id')
                  ->nullable()
                  ->constrained('vehicle_types')
                  ->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transport_requests');
    }
};
