<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pooling_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();
            $table->string('route');
            $table->integer('max_capacity');
            $table->integer('current_capacity')->default(0);
            $table->dateTime('departure_time');
            $table->enum('status', ['open', 'full', 'departed'])->default('open');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pooling_groups');
    }
};
