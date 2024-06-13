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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_service_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('standard_id')->nullable()->constrained()->cascadeOnDelete();
            $table->unsignedInteger('standard_quantity');
            $table->boolean('need_materials')->default(0);
            $table->decimal('cleaning_materials_cost', 8,3)->default(0);
            $table->string('title_ar');
            $table->string('title_en');
            $table->decimal('price', 8,3)->default(0);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
