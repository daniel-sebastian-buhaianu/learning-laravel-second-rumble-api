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
        Schema::create('channels', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('url')->unique();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->string('banner')->nullable();
            $table->string('avatar')->nullable();
            $table->unsignedBigInteger('followers_count')->default(0);
            $table->unsignedSmallInteger('videos_count')->default(0);
            $table->date('joined_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('channels');
    }
};
