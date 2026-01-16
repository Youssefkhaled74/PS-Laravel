<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('brand_vendor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
            $table->foreignId('brand_id')->constrained('brands')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['vendor_id', 'brand_id']);
            $table->index('vendor_id');
            $table->index('brand_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('brand_vendor');
    }
};
