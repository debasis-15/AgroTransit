<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pooled_trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')
                  ->constrained('vehicles')
                  ->onDelete('cascade');
            $table->foreignId('driver_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->json('route')->nullable();
            $table->decimal('total_cost', 10, 2);
            $table->string('status')->default('pending');
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pooled_trips');
    }
};
