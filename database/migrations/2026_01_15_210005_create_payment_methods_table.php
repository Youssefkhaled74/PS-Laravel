<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // apple_pay, mada, tamara, tabby, paymob
            $table->json('name'); // {en: "Apple Pay", ar: "آبل باي"}
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_methods');
    }
};
