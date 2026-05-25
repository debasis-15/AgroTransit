<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')
                  ->constrained('transport_requests')
                  ->onDelete('cascade');
            $table->foreignId('vehicle_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->foreignId('driver_id')
                  ->constrained('drivers')
                  ->onDelete('cascade');
            $table->string('tracking_code')->unique();
            $table->enum('shipment_status', ['pickup', 'in_transit', 'delivered', 'delayed'])->default('pickup');
            $table->dateTime('estimated_arrival')->nullable();
            $table->decimal('current_latitude', 10, 7)->nullable();
            $table->decimal('current_longitude', 10, 7)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
