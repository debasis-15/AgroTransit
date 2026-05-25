<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')
                  ->constrained('transport_owners')
                  ->onDelete('cascade');
            $table->foreignId('driver_id')
                  ->nullable()
                  ->constrained('drivers')
                  ->nullOnDelete();
            $table->string('registration_number')->unique();
            $table->foreignId('vehicle_type_id')
                  ->nullable()
                  ->constrained('vehicle_types')
                  ->nullOnDelete();
            $table->integer('capacity_kg');
            $table->integer('current_load')->default(0);
            $table->boolean('cold_storage')->default(false);
            $table->string('fuel_type')->nullable();
            $table->string('current_location')->nullable();
            $table->enum('tracking_status', ['available', 'busy', 'maintenance', 'in_transit'])->default('available');
            $table->date('insurance_expiry')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
