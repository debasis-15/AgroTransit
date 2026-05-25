<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('farmer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->string('farm_name');
            $table->string('primary_crop');
            $table->string('location');
            $table->string('district');
            $table->string('state');
            $table->decimal('farm_size', 8, 2)->nullable();
            $table->boolean('verified')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('farmer_profiles');
    }
};
