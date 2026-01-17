<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('vendor_otps')) return;

        if (! Schema::hasColumn('vendor_otps', 'reset_token_hash')) {
            Schema::table('vendor_otps', function (Blueprint $table) {
                $table->string('reset_token_hash')->nullable()->after('attempts');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('vendor_otps', 'reset_token_hash')) {
            Schema::table('vendor_otps', function (Blueprint $table) {
                $table->dropColumn('reset_token_hash');
            });
        }
    }
};
