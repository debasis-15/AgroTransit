<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicle_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('drivers')->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->string('status')->default('pending'); // pending, accepted, rejected
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_requests');
    }
};
