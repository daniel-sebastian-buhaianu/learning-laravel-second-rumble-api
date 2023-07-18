<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('channel_id')->constrained()->cascadeOnDelete();
            $table->string('url');
            $table->string('src');
            $table->string('title');
            $table->text('description');
            $table->unsignedSmallInteger('likes_count');
            $table->unsignedSmallInteger('dislikes_count');
            $table->unsignedSmallInteger('comments_count');
            $table->unsignedBigInteger('views_count');
            $table->date('uploaded_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
