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
        Schema::create('groups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('group_id', 10);
            $table->integer('semester');
            $table->string('room', 250);
            $table->uuid('period_id');
            $table->foreign('period_id')->references('id')->on('periods');
            $table->integer('day_in_week');
            $table->json('weeks');
            $table->integer('count_student');
            $table->uuid('subject_id');
            $table->foreign('subject_id')->references('id')->on('subjects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groups');
    }
};
