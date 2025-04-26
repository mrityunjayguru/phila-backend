<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable();
            $table->string('image')->nullable();
            $table->string('address')->nullable();
            $table->text('description')->nullable();
            $table->integer('place_id')->nullable();
            $table->integer('nearest_stop')->nullable();
            $table->string('website')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('mon_sat')->nullable();
            $table->string('sunnday')->nullable();
            $table->string('adult_charges')->nullable();
            $table->string('student_charges')->nullable();
			$table->string('ticket_booking_url')->nullable();
			$table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
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
        Schema::dropIfExists('offers');
    }
}
