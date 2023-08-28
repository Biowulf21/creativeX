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
        Schema::create('tweets', function (Blueprint $table) {
            $table->id();
            $table->string('tweet_body', 280);

            $table->unsignedBigInteger('replying_to')->nullable();
            $table->foreign('replying_to')->references('id')->on('tweets');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('likes_count')->default(0);
            $table->unsignedBigInteger('retweets_count')->default(0);
            $table->boolean('is_retweet')->default(false);

            $table->string('tweet_attachment_link')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tweets');
    }
};
