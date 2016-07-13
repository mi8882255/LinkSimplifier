<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Comments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Comment: uid, created_at, ip, text
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->foreign('bookmark_id')->references('id')->on('bookmark');
            $table->string('ip');
            $table->text('body');
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
        //

    }
}
