<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('vendor_shipping_details')) return;

        Schema::create('vendor_shipping_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade')->unique();
            $table->unsignedBigInteger('within_city_fee')->default(0); // stored in cents
            $table->unsignedBigInteger('within_ksa_fee')->default(0);
            $table->unsignedBigInteger('ksa_to_gcc_fee')->default(0);
            $table->unsignedBigInteger('ksa_to_world_fee')->default(0);
            $table->string('currency', 8)->default('SAR');
            $table->enum('status', ['active','inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_shipping_details');
    }
};
