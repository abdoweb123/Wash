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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('title_ar');
            $table->string('title_en');

            $table->string('currancy_code_ar')->nullable();
            $table->string('currancy_code_en')->nullable();

            $table->decimal('currancy_value', 5, 3)->default(0.000);
            $table->string('phone_code')->nullable();
            $table->string('country_code')->nullable();
            $table->integer('length')->default(10);
            $table->integer('decimals')->default(3);

            $table->string('lat')->nullable();
            $table->string('long')->nullable();

            $table->string('image')->nullable();

            $table->boolean('status')->default(1);
            $table->boolean('accept_orders')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
