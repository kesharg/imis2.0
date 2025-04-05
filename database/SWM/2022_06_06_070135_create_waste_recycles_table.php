<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWasteRecyclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('swm.waste_recycles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transfer_station_id');
            $table->foreign('transfer_station_id')
            ->references('id')->on('swm.transfer_stations')->onDelete('cascade');
            $table->float('volume');
            $table->datetime('date_time');
            $table->float('rate');
            $table->float('total_price');
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
        Schema::dropIfExists('swm.waste_recycles');
    }
}
