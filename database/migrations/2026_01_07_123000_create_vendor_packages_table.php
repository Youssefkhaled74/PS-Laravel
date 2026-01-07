<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('vendor_packages')) {
            return;
        }

        Schema::create('vendor_packages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('key')->unique();
            $table->json('name');
            $table->unsignedBigInteger('monthly_price')->default(0);
            $table->unsignedBigInteger('yearly_price')->default(0);
            $table->string('currency')->default('SAR');
            $table->integer('sort_order')->default(0);
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendor_packages');
    }
};
