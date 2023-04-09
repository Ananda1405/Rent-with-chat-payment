<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('houses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('house_code');
            $table->string('address');
            $table->integer('area_id');
            $table->integer('user_id');
            $table->string('contact');
            $table->integer('number_of_room');
            $table->integer('number_of_toilet');
            $table->integer('number_of_belcony');
            $table->string('description');
            $table->string('month');
            $table->integer('rent');
            $table->string('map_link')->nullable();
            $table->string('featured_image');
            $table->text('images');
            // $table->text('video');
            $table->string('status')->default(0);  //0 means Not-available
            $table->string('a_status')->default(0);  //0 means Pending For approval
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
        Schema::dropIfExists('houses');
    }
}
