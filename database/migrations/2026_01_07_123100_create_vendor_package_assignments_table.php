<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('vendor_package_assignments')) {
            return;
        }

        Schema::create('vendor_package_assignments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('vendor_package_id');
            $table->string('billing_cycle');
            $table->unsignedBigInteger('price')->default(0);
            $table->string('currency')->default('SAR');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->string('status')->default('active');
            $table->unsignedBigInteger('assigned_by_admin_id')->nullable();
            $table->timestamps();

            $table->index('vendor_id');
            $table->index('vendor_package_id');
        });

        // Add foreign keys only if referenced tables exist. This avoids FK errors if migrations run in different order.
        if (Schema::hasTable('vendor_packages')) {
            Schema::table('vendor_package_assignments', function (Blueprint $table) {
                $table->foreign('vendor_package_id')->references('id')->on('vendor_packages')->onDelete('cascade');
            });
        }

        if (Schema::hasTable('vendors')) {
            Schema::table('vendor_package_assignments', function (Blueprint $table) {
                $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            });
        }

        if (Schema::hasTable('admins')) {
            Schema::table('vendor_package_assignments', function (Blueprint $table) {
                $table->foreign('assigned_by_admin_id')->references('id')->on('admins')->onDelete('set null');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('vendor_package_assignments');
    }
};
