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
            $table->unsignedTinyInteger('result')->nullable();  // 1 - corret | 2 - incorrect | 3 - missed
            $table->foreignId('attempt_id')->constrained('test_attempts')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('option_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('answer_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('given_answers');
    }
};
