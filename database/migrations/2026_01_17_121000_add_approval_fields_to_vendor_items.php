<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('vendor_items')) return;

        Schema::table('vendor_items', function (Blueprint $table) {
            if (! Schema::hasColumn('vendor_items', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('status');
            }
            if (! Schema::hasColumn('vendor_items', 'approved_by_admin_id')) {
                $table->unsignedBigInteger('approved_by_admin_id')->nullable()->after('rejection_reason');
                $table->foreign('approved_by_admin_id')->references('id')->on('admins')->nullOnDelete();
            }
            if (! Schema::hasColumn('vendor_items', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('approved_by_admin_id');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('vendor_items')) return;

        Schema::table('vendor_items', function (Blueprint $table) {
            if (Schema::hasColumn('vendor_items', 'approved_at')) $table->dropColumn('approved_at');
            if (Schema::hasColumn('vendor_items', 'approved_by_admin_id')) {
                $table->dropForeign(['approved_by_admin_id']);
                $table->dropColumn('approved_by_admin_id');
            }
            if (Schema::hasColumn('vendor_items', 'rejection_reason')) $table->dropColumn('rejection_reason');
        });
    }
};
