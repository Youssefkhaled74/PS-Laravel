<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vendor_payment_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
            $table->foreignId('vendor_package_assignment_id')->constrained('vendor_package_assignments')->onDelete('cascade');
            $table->foreignId('payment_method_id')->constrained('payment_methods')->onDelete('cascade');
            $table->enum('billing_period', ['monthly', 'yearly']);
            $table->unsignedBigInteger('amount')->default(0); // Amount in cents
            $table->unsignedBigInteger('vat')->default(0); // VAT in cents
            $table->unsignedBigInteger('total')->default(0); // Total in cents
            $table->enum('status', ['initiated', 'pending', 'paid', 'failed'])->default('initiated');
            $table->string('reference')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            
            $table->index(['vendor_id', 'status']);
            $table->index('vendor_package_assignment_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendor_payment_attempts');
    }
};
