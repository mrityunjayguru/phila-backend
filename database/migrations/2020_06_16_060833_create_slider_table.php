<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSliderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slider', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->string('title')->nullable();
            $table->enum('device',['Web','Mobile'])->nullable();
            $table->string('slug')->nullable();
            $table->enum('status',['active','inactive'])->default('active');
            $table->timestamps();
        });
		
		
		// Slides
		Schema::create('slides', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->integer('slider_id');
			$table->integer('priority')->nullable();
            $table->string('title')->nullable();
            $table->string('tagline')->nullable();
			$table->string('image')->nullable();
            $table->enum('is_clickable',['Yes','No'])->default('No');
            $table->string('redirect_to')->nullable();
            $table->string('button_text')->nullable();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('slider');
		Schema::dropIfExists('slides');
    }
}
