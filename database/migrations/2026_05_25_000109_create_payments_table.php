<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->foreignId('payer_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method');
            $table->string('transaction_id')->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
