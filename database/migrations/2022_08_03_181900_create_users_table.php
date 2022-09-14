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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 250)->nullable();
            $table->string('email', 250)->unique();
            $table->string('tdt_password', 250)->nullable();
            $table->longText('avatar')->nullable();
            $table->integer('role')->default(1);
            $table->boolean('active')->default(0);
            $table->integer('count_system_mail_daily')->default(0);
            $table->string('google_id',250)->nullable();
            $table->boolean('is_notify_score')->default(false);
            $table->integer('notify_notification')->default(null)->nullable();
            $table->boolean('is_auto_read_notification')->default(false);
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
        Schema::dropIfExists('users');
    }
};
