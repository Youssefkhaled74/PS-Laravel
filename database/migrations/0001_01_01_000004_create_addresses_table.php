<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('label')->nullable();
            $table->string('country')->nullable()->default('Saudi Arabia');
            $table->string('city');
            $table->string('district')->nullable();
            $table->string('street');
            $table->string('building_no')->nullable();
            $table->string('apartment_no')->nullable();
            $table->string('floor')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('phone')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
