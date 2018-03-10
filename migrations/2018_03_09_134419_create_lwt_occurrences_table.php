<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLwtOccurrencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lwt_occurrences', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('occurred_at');
            $table->string('log_location');
            $table->integer('lwt_whoops_id')->unsigned();
            $table->timestamps();

            $table->foreign('lwt_whoops_id')
                ->references('id')
                ->on('lwt_whoopses')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lwt_occurrences');
    }
}
