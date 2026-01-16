<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('vendors', function (Blueprint $table) {
            if (!Schema::hasColumn('vendors', 'onboarding_step')) {
                $table->string('onboarding_step')->nullable()->after('status');
            }
            if (!Schema::hasColumn('vendors', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('onboarding_step');
            }
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
    }

    public function down()
    {
        Schema::table('vendors', function (Blueprint $table) {
            if (Schema::hasColumn('vendors', 'onboarding_step')) {
                $table->dropColumn('onboarding_step');
            }
            if (Schema::hasColumn('vendors', 'rejection_reason')) {
                $table->dropColumn('rejection_reason');
            }
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
