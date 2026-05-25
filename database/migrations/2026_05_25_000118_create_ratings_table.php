<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->foreignId('farmer_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->foreignId('pooled_trip_id')
                  ->nullable()
                  ->constrained('pooled_trips')
                  ->nullOnDelete();
            $table->integer('rating');
            $table->text('review')->nullable();
            $table->boolean('on_time')->default(true);
            $table->boolean('safe_delivery')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
