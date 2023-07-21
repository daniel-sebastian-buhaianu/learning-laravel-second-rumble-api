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
            $table->string('channel_id');
            $table->string('url')->unique();
            $table->string('src')->unique();
            $table->string('name');
            $table->string('thumbnail')->default('https://www.pngkit.com/png/full/267-2678423_bacteria-video-thumbnail-default.png');
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('likes_count')->default(0);
            $table->unsignedSmallInteger('dislikes_count')->default(0);
            $table->unsignedSmallInteger('comments_count')->default(0);
            $table->unsignedBigInteger('views_count')->default(0);
            $table->date('uploaded_at');
            $table->timestamps();

            $table->foreign('channel_id')
                ->references('id')
                ->on('channels')
                ->constrained()
                ->cascadeOnDelete();

            $table->index('name');
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
