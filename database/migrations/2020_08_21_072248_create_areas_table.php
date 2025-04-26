<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('areas', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->string('title')->nullable();
          $table->bigInteger('city_id')->unsigned()->nullable();
		  $table->integer('priority')->nullable();
          $table->integer('pincode')->nullable();
          $table->string('latitude')->nullable();
          $table->string('longitude')->nullable();
		  $table->enum('status',['active','inactive'])->default('active');
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
        Schema::dropIfExists('areas');
    }
}