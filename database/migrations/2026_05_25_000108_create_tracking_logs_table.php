<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracking_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->decimal('speed', 5, 2)->nullable();
            $table->timestamp('recorded_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracking_logs');
    }
};
