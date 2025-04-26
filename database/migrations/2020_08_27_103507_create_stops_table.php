<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stops', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('title')->nullable();
			$table->string('image')->nullable();
			$table->string('stop_image')->nullable();
			$table->integer('priority')->nullable();
			$table->enum('type',['tour','fairmount_park_loop','mix'])->nullable();
			$table->enum('for_type',['tour','fairmount_park_loop'])->nullable();
			$table->enum('color',['red','blue','mix'])->nullable();
			$table->string('time')->nullable();
			$table->text('description')->nullable();
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
        Schema::dropIfExists('stops');
    }
}