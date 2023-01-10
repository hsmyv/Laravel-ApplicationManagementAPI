<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailconfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mailconfig', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('name');
            $table->string('host');
            $table->string('encryption');
            $table->string('port');
            $table->string('username');
            $table->string('password');
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
        Schema::dropIfExists('mailconfig');
    }
}
