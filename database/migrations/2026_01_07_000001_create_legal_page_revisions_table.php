<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('legal_page_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('legal_page_id')->constrained('legal_pages')->cascadeOnDelete();
            $table->json('title')->nullable();
            $table->json('content')->nullable();
            $table->string('status')->nullable();
            $table->integer('version')->default(1);
            $table->foreignId('updated_by_admin_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('legal_page_revisions');
    }
};
