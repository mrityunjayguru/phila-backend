<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('imei_number');
            $table->string('device_id');
            $table->string('device_type');
            $table->string('title')->nullable();
            $table->string('latitude')->nullable();
			$table->string('longitude')->nullable();
			$table->datetime('last_update')->nullable();
			$table->enum('live_status',['online','offline'])->default('offline');
			$table->integer('last_visited_stop')->nullable();
			$table->enum('status',['active','inactive'])->default('active');
			$table->enum('security',['public','private'])->default('public');
            $table->timestamps();
            $table->datetime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buses_tables');
    }
}
