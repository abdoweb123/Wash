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
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->string('title_ar')->nullable();
            $table->string('title_en')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->decimal('delivery_cost', 9, 3)->default(0.000);
            $table->decimal('delivery_time', 9, 2)->default(0.00);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regions');
    }
};
