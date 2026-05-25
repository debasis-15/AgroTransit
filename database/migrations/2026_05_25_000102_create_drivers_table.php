<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->string('license_number');
            $table->date('license_expiry');
            $table->integer('experience_years')->default(0);
            $table->decimal('rating', 2, 1)->default(5.0);
            $table->boolean('available')->default(true);
            $table->boolean('verified')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
