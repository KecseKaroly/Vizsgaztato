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
        Schema::create('given_answers', function (Blueprint $table) {
            $table->id();
            //$table->foreignId('question_id');
            $table->foreignId('answer_id');
            $table->foreignId('attempt_id');
            $table->foreignId('question_id');
            $table->string('given');
            $table->timestamps();   

            $table->foreign('attempt_id')->references('id')->on('test_attempts');
            $table->foreign('question_id')->references('id')->on('questions');
            $table->foreign('answer_id')->references('id')->on('answers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('given_answers');
    }
};
