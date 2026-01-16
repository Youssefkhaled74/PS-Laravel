<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('vendor_packages', function (Blueprint $table) {
            $table->json('features')->nullable()->after('yearly_price');
        });
    }

    public function down()
    {
        Schema::table('vendor_packages', function (Blueprint $table) {
            $table->dropColumn('features');
        });
    }
};
