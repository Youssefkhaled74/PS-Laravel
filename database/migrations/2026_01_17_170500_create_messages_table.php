<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (! Schema::hasTable('messages')) {
            Schema::create('messages', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('conversation_id');
                $table->enum('sender_type', ['user','vendor']);
                $table->unsignedBigInteger('sender_id');
                $table->text('body')->nullable();
                $table->enum('message_type', ['text','image','file'])->default('text');
                $table->string('attachment_path')->nullable();
                $table->timestamp('read_at')->nullable();
                $table->timestamps();

                $table->index(['conversation_id']);
                $table->index(['sender_type','sender_id']);

                $table->foreign('conversation_id')->references('id')->on('conversations')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('messages');
    }
};
