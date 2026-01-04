<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('otps', function (Blueprint $table) {
            $table->id();
            $table->string('country_code', 10)->nullable();
            $table->string('phone');
            $table->string('purpose', 50); // REGISTER_VERIFY | PASSWORD_RESET
            $table->string('code_hash');
            $table->timestamp('expires_at');
            $table->timestamp('resend_available_at')->nullable();
            $table->unsignedInteger('attempts_count')->default(0);
            $table->timestamp('verified_at')->nullable();
            $table->string('reset_token_hash')->nullable();
            $table->timestamps();

            $table->index(['country_code','phone']);
            $table->index(['purpose']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('otps');
    }
};
