<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllkeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
         Schema::table('rooms', function ($table) {
        $table->foreign('user_id')->references('id')->on('users');
      });
          Schema::table('room_photos', function ($table) {
        $table->foreign('room_id')->references('id')->on('rooms');
      });
           Schema::table('room_ratings', function ($table) {
        $table->foreign('room_id')->references('id')->on('rooms');
        $table->foreign('user_id')->references('id')->on('users');

      });
          
             Schema::table('room_reservations', function ($table) {
        $table->foreign('room_id')->references('id')->on('rooms');
        $table->foreign('user_id')->references('id')->on('users');

      });
              Schema::table('likes', function ($table) {
        $table->foreign('room_id')->references('id')->on('rooms');
        $table->foreign('user_id')->references('id')->on('users');
        
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
        Schema::table('likes', function ($table) {
        $table->dropForeign('likes_user_id_foreign');
        $table->dropForeign('likes_room_id_foreign');
        
      });
        Schema::table('room_reservations', function ($table) {
        $table->dropForeign('room_reservations_user_id_foreign');
        $table->dropForeign('room_reservations_room_id_foreign');
        
      });
        Schema::table('room_ratings', function ($table) {
        $table->dropForeign('room_ratings_user_id_foreign');
        $table->dropForeign('room_ratings_room_id_foreign');
        
      });
        Schema::table('room_photos', function ($table) {
        $table->dropForeign('room_photos_room_id_foreign');
      });
        Schema::table('rooms', function ($table) {
        $table->dropForeign('rooms_user_id_foreign');
      });




    }
}
