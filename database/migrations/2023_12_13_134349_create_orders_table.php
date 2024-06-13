<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('address_id')->constrained()->nullOnDelete();
            $table->foreignId('payment_method_id')->nullable()->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->time('time');
            $table->decimal('sub_total', 8,3)->nullable();
            $table->decimal('vat_cost', 8,3)->nullable();
            $table->decimal('net_total', 8,3)->nullable();
            $table->string('transaction_number')->nullable();
            $table->boolean('is_paid')->default(0);
            $table->enum('status', ['approved', 'onway', 'processing', 'done'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
