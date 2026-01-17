<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (! Schema::hasTable('conversations')) {
            Schema::create('conversations', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('vendor_id');
                $table->timestamp('last_message_at')->nullable();
                $table->integer('user_unread_count')->default(0);
                $table->integer('vendor_unread_count')->default(0);
                $table->timestamps();

                $table->unique(['user_id', 'vendor_id']);
                $table->index(['user_id']);
                $table->index(['vendor_id']);
                $table->index(['last_message_at']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('conversations');
    }
};
