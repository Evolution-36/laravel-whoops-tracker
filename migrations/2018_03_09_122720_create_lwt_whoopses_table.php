<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLwtWhoopsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lwt_whoopses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hash');
            $table->text('file');
            $table->string('line');
            $table->text('message');
            $table->string('log_level');
            $table->string('exception_class');
            $table->integer('status');
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
        Schema::dropIfExists('lwt_whoopses');
    }
}
