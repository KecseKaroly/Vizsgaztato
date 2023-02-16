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
        Schema::create('tests_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained();
            $table->foreignId('group_id')->constrained();
            $table->dateTime('enabled_from')->nullable()->default(NULL);
            $table->dateTime('enabled_until')->nullable()->default(NULL);
            $table->timestamps();

            //$table->foreign('test_id')->references('id')->on('tests')->onUpdate('cascade')->onDelete('cascade');
            //$table->foreign('group_id')->references('id')->on('groups')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tests_groups');
    }
};
