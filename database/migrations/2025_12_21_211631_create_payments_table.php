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
            $table->foreignId('donation_id')->constrained()->onDelete('cascade');
            $table->string('method');
            $table->bigInteger('amount');
            $table->enum('status', ['pending', 'success', 'failed', 'refunded'])->default('pending');
            $table->string('transaction_id')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index('transaction_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
