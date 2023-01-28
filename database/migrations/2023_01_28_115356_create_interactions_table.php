<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interactions', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('post_id')->nullable()->constrained('posts')->onDelete('cascade');
            $table->integer('comment_id')->nullable()->constrained('comments')->onDelete('cascade');
            $table->enum('interact', ['like', 'dislike', 'funny', 'love']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('interactions');
    }
};
