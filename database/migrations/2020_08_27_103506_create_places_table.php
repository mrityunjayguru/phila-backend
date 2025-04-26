<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('places', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->string('type');
			$table->string('title')->nullable();
            $table->string('image')->nullable();
            $table->string('address')->nullable();
            $table->text('description')->nullable();
            $table->integer('nearest_stop')->nullable();
            $table->string('website')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('mon_sat')->nullable();
            $table->string('is_hours')->nullable();
            $table->string('monday')->nullable();
            $table->string('tuesday')->nullable();
            $table->string('wednesday')->nullable();
            $table->string('thursday')->nullable();
            $table->string('friday')->nullable();
            $table->string('saturday')->nullable();
            $table->string('sunday')->nullable();
            $table->integer('is_charges')->default('0');
            $table->string('charges')->nullable();
			$table->string('ticket_booking_url')->nullable();
			$table->string('latitude')->nullable();
			$table->string('longitude')->nullable();
			$table->text('google_business_url')->nullable();
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
        Schema::dropIfExists('places');
    }
}