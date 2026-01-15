<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('vendors', function (Blueprint $table) {
            // Add new columns if they don't exist
            if (!Schema::hasColumn('vendors', 'full_name')) {
                $table->string('full_name')->nullable()->after('id');
            }
            if (!Schema::hasColumn('vendors', 'second_phone')) {
                $table->string('second_phone')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('vendors', 'avatar_path')) {
                $table->string('avatar_path')->nullable()->after('bio');
            }
            if (!Schema::hasColumn('vendors', 'national_address')) {
                $table->string('national_address')->nullable()->after('location_text');
            }
            if (!Schema::hasColumn('vendors', 'lat')) {
                $table->decimal('lat', 10, 8)->nullable()->after('national_address');
            }
            if (!Schema::hasColumn('vendors', 'lng')) {
                $table->decimal('lng', 11, 8)->nullable()->after('lat');
            }
        });
        
        // Make email nullable using raw SQL to avoid unique constraint conflict
        DB::statement('ALTER TABLE `vendors` MODIFY `email` VARCHAR(255) NULL');
    }

    public function down()
    {
        Schema::table('vendors', function (Blueprint $table) {
            if (Schema::hasColumn('vendors', 'full_name')) {
                $table->dropColumn('full_name');
            }
            if (Schema::hasColumn('vendors', 'second_phone')) {
                $table->dropColumn('second_phone');
            }
            if (Schema::hasColumn('vendors', 'avatar_path')) {
                $table->dropColumn('avatar_path');
            }
            if (Schema::hasColumn('vendors', 'national_address')) {
                $table->dropColumn('national_address');
            }
            if (Schema::hasColumn('vendors', 'lat')) {
                $table->dropColumn('lat');
            }
            if (Schema::hasColumn('vendors', 'lng')) {
                $table->dropColumn('lng');
            }
        });
    }
};
