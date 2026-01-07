<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('vendor_business_profiles')) {
            return;
        }

        Schema::create('vendor_business_profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vendor_id')->unique();
            $table->string('commercial_name')->nullable();
            $table->unsignedBigInteger('activity_id')->nullable();
            $table->string('id_number')->nullable();
            $table->string('commercial_register_number')->nullable();
            $table->string('freelance_doc_number')->nullable();
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->foreign('bank_id')->references('id')->on('banks')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendor_business_profiles');
    }
};
