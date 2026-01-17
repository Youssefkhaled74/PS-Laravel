<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('vendor_items')) return;

        Schema::create('vendor_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('piece_type_id')->nullable()->constrained('piece_types')->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
            $table->foreignId('gender_id')->nullable()->constrained('genders')->nullOnDelete();
            $table->foreignId('size_id')->nullable()->constrained('sizes')->nullOnDelete();
            $table->foreignId('color_id')->nullable()->constrained('colors')->nullOnDelete();
            $table->string('name');
            $table->unsignedInteger('quantity_available')->default(0);
            $table->unsignedInteger('quantity_per_client')->nullable();
            $table->decimal('weight', 10, 2)->nullable();
            $table->bigInteger('price')->default(0);
            $table->bigInteger('discount_price')->nullable();
            $table->timestamp('discount_ends_at')->nullable();
            $table->string('warranty')->nullable();
            $table->string('promo_title')->nullable();
            $table->boolean('is_taxable')->default(false);
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendor_items');
    }
};
