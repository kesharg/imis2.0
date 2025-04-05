<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferLogInsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('swm.transfer_log_ins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('route_id');
            $table->foreign('route_id')
            ->references('id')->on('swm.routes')->onDelete('cascade');
            $table->unsignedBigInteger('transfer_station_id');
            $table->foreign('transfer_station_id')
            ->references('id')->on('swm.transfer_stations')->onDelete('cascade');
            $table->integer('type_of_waste');
            $table->float('volume');
            $table->timestamp('date_time');
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
        Schema::dropIfExists('swm.transfer_log_ins');
    }
}
