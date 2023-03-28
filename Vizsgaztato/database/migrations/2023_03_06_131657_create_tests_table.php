<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_exam');
            $table->string('title');
            $table->integer('maxAttempts');
            $table->integer('duration');
            $table->boolean('resultsViewable');
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('module_id')->nullable()->default(NULL)->constrained()->onDelete('cascade');
            $table->dateTime('enabled_from')->nullable()->default(NULL);
            $table->dateTime('enabled_until')->nullable()->default(NULL);

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
        Schema::dropIfExists('tests');
    }
};
