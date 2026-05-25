<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qr_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->string('qr_code');
            $table->foreignId('verified_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->enum('status', ['pending', 'verified'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qr_verifications');
    }
};
