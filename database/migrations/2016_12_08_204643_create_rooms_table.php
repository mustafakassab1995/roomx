<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('rooms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('user_id')->unsigned();
            $table->integer('number_of_guests');
            $table->integer('number_of_beds');
            $table->integer('number_of_baths');
            $table->integer('number_of_rooms');
            $table->double('rating')->default(0);
            $table->string('location');
            $table->string('description');
            $table->boolean('tv');
            $table->boolean('wifi');
            $table->boolean('pool');
            $table->boolean('air_condition');
            $table->boolean('kitchen');

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
         Schema::dropIfExists('rooms');
    }
}
