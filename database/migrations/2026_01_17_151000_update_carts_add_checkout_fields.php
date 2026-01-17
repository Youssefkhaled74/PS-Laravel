<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('carts')) {
            Schema::table('carts', function (Blueprint $table) {
                if (! Schema::hasColumn('carts', 'address_id')) {
                    $table->foreignId('address_id')->nullable()->constrained('addresses')->nullOnDelete()->after('vendor_id');
                }
                if (! Schema::hasColumn('carts', 'payment_method_id')) {
                    $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->nullOnDelete()->after('address_id');
                }
                if (! Schema::hasColumn('carts', 'shipping_fee')) {
                    $table->bigInteger('shipping_fee')->default(0)->after('payment_method_id');
                }
                if (! Schema::hasColumn('carts', 'vat')) {
                    $table->bigInteger('vat')->default(0)->after('shipping_fee');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('carts')) {
            Schema::table('carts', function (Blueprint $table) {
                if (Schema::hasColumn('carts', 'vat')) $table->dropColumn('vat');
                if (Schema::hasColumn('carts', 'shipping_fee')) $table->dropColumn('shipping_fee');
                if (Schema::hasColumn('carts', 'payment_method_id')) $table->dropForeign(['payment_method_id']);
                if (Schema::hasColumn('carts', 'payment_method_id')) $table->dropColumn('payment_method_id');
                if (Schema::hasColumn('carts', 'address_id')) $table->dropForeign(['address_id']);
                if (Schema::hasColumn('carts', 'address_id')) $table->dropColumn('address_id');
            });
        }
    }
};
