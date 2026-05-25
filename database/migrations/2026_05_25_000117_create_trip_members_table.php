<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trip_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pooled_trip_id')
                  ->constrained('pooled_trips')
                  ->onDelete('cascade');
            $table->foreignId('transport_request_id')
                  ->constrained('transport_requests')
                  ->onDelete('cascade');
            $table->foreignId('farmer_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->integer('weight_kg');
            $table->decimal('cost_share', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trip_members');
    }
};
