<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('carts')) {
            Schema::create('carts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('vendor_id')->nullable()->constrained('vendors')->nullOnDelete();
                $table->enum('status', ['active', 'checked_out'])->default('active');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('cart_items')) {
            Schema::create('cart_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('cart_id')->constrained('carts')->onDelete('cascade');
                $table->foreignId('vendor_item_id')->constrained('vendor_items')->onDelete('cascade');
                $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
                $table->unsignedInteger('quantity')->default(1);
                $table->bigInteger('unit_price')->default(0); // stored as cents
                $table->timestamps();

                $table->unique(['cart_id', 'vendor_item_id']);
                $table->index('cart_id');
                $table->index('vendor_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('cart_items')) Schema::dropIfExists('cart_items');
        if (Schema::hasTable('carts')) Schema::dropIfExists('carts');
    }
};
