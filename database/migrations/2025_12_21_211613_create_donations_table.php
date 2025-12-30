<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('donor_name');
            $table->string('donor_email');
            $table->bigInteger('amount');
            $table->text('note')->nullable();
            $table->enum('payment_status', ['pending', 'success', 'failed', 'expired'])->default('pending');
            $table->string('midtrans_order_id')->nullable();
            $table->string('midtrans_transaction_id')->nullable();
            $table->string('midtrans_snap_token')->nullable();
            $table->json('payment_data')->nullable();
            $table->timestamps();

            $table->index('midtrans_order_id');
            $table->index('payment_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
