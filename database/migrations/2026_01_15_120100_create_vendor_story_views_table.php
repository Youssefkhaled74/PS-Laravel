<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_story_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_story_id')->constrained('vendor_stories')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->dateTime('viewed_at');
            $table->timestamps();

            $table->unique(['user_id', 'vendor_story_id']);
            $table->index('viewed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_story_views');
    }
};
