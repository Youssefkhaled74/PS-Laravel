<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
                $table->foreignId('address_id')->nullable()->constrained('addresses')->nullOnDelete();
                $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->nullOnDelete();
                $table->bigInteger('subtotal')->default(0);
                $table->bigInteger('shipping_fee')->default(0);
                $table->bigInteger('vat')->default(0);
                $table->bigInteger('total')->default(0);
                $table->string('status')->default('pending');
                $table->text('note')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('order_items')) {
            Schema::create('order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
                $table->foreignId('vendor_item_id')->constrained('vendor_items')->onDelete('cascade');
                $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
                $table->unsignedInteger('quantity')->default(1);
                $table->bigInteger('unit_price')->default(0);
                $table->bigInteger('line_total')->default(0);
                $table->timestamps();

                $table->index('order_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('order_items')) Schema::dropIfExists('order_items');
        if (Schema::hasTable('orders')) Schema::dropIfExists('orders');
    }
};
