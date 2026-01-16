<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('vendor_otps')) {
            return;
        }

        Schema::create('vendor_otps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->onDelete('cascade');
            $table->string('phone');
            $table->string('purpose', 50)->default('VENDOR_REGISTER_VERIFY'); // VENDOR_REGISTER_VERIFY | VENDOR_PASSWORD_RESET
            $table->string('otp_hash');
            $table->timestamp('expires_at');
            $table->timestamp('consumed_at')->nullable();
            $table->timestamp('resend_available_at')->nullable();
            $table->unsignedInteger('attempts')->default(0);
            $table->timestamps();

            $table->index(['vendor_id', 'phone']);
            $table->index('purpose');
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendor_otps');
    }
};
