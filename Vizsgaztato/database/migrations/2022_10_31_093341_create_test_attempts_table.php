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
        Schema::create('test_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('group_id');
            $table->foreignId('test_id');
            $table->integer('maxScore');
            $table->integer('achievedScore');
            $table->boolean('resultsViewable');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('test_id')->references('id')->on('tests')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('groups')->onUpdate('cascade')->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_attempts');
    }
};
