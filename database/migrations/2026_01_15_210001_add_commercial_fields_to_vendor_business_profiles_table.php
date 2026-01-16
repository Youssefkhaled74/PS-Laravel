<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('vendor_business_profiles', function (Blueprint $table) {
            $table->string('id_card_file')->nullable()->after('bank_account_number');
            $table->string('commercial_file')->nullable()->after('id_card_file');
            $table->boolean('accept_terms')->default(false)->after('commercial_file');
        });
    }

    public function down()
    {
        Schema::table('vendor_business_profiles', function (Blueprint $table) {
            $table->dropColumn(['id_card_file', 'commercial_file', 'accept_terms']);
        });
    }
};
