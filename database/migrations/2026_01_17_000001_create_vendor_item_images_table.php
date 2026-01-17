<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('vendor_item_images')) return;

        Schema::create('vendor_item_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_item_id')->constrained('vendor_items')->onDelete('cascade');
            $table->string('path');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendor_item_images');
    }
};
