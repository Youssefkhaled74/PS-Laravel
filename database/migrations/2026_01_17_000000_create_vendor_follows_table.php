<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('vendor_follows')) {
            Schema::create('vendor_follows', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->unsignedBigInteger('vendor_id')->index();
                $table->enum('status', ['active', 'muted'])->default('active');
                $table->timestamps();

                $table->unique(['user_id', 'vendor_id']);
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            });
        } else {
            // ensure required columns exist
            Schema::table('vendor_follows', function (Blueprint $table) {
                if (! Schema::hasColumn('vendor_follows', 'status')) {
                    $table->enum('status', ['active', 'muted'])->default('active')->after('vendor_id');
                }
                if (! Schema::hasColumn('vendor_follows', 'created_at')) {
                    $table->timestamps();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('vendor_follows')) {
            Schema::dropIfExists('vendor_follows');
        }
    }
};
