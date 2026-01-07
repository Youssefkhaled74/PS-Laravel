<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('vendor_payment_selections')) {
            return;
        }

        Schema::create('vendor_payment_selections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('vendor_package_assignment_id')->nullable();
            $table->string('payment_method');
            $table->string('status')->default('selected');
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->foreign('vendor_package_assignment_id')->references('id')->on('vendor_package_assignments')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendor_payment_selections');
    }
};
