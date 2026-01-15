<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Update vendors table with OTP tracking fields
        Schema::table('vendors', function (Blueprint $table) {
            if (!Schema::hasColumn('vendors', 'otp_last_sent_at')) {
                $table->timestamp('otp_last_sent_at')->nullable()->after('phone_verified_at');
            }
            if (!Schema::hasColumn('vendors', 'otp_locked_until')) {
                $table->timestamp('otp_locked_until')->nullable()->after('otp_last_sent_at');
            }
            if (!Schema::hasColumn('vendors', 'otp_attempts')) {
                $table->integer('otp_attempts')->default(0)->after('otp_locked_until');
            }
        });

        // Create vendor_otps table for OTP history
        if (!Schema::hasTable('vendor_otps')) {
            Schema::create('vendor_otps', function (Blueprint $table) {
                $table->id();
                $table->foreignId('vendor_id')->constrained('vendors')->cascadeOnDelete();
                $table->string('phone');
                $table->string('otp_hash');
                $table->timestamp('expires_at');
                $table->timestamp('consumed_at')->nullable();
                $table->timestamp('resend_available_at');
                $table->integer('attempts')->default(0);
                $table->timestamps();

                $table->index(['vendor_id', 'phone']);
                $table->index('expires_at');
                $table->index('consumed_at');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('vendor_otps');
        
        Schema::table('vendors', function (Blueprint $table) {
            if (Schema::hasColumn('vendors', 'otp_last_sent_at')) {
                $table->dropColumn('otp_last_sent_at');
            }
            if (Schema::hasColumn('vendors', 'otp_locked_until')) {
                $table->dropColumn('otp_locked_until');
            }
            if (Schema::hasColumn('vendors', 'otp_attempts')) {
                $table->dropColumn('otp_attempts');
            }
        });
    }
};
