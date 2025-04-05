<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferLogOutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('swm.transfer_log_outs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transfer_station_id');
            $table->foreign('transfer_station_id')
            ->references('id')->on('swm.transfer_stations')->onDelete('cascade');
            $table->unsignedBigInteger('landfill_site_id');
            $table->foreign('landfill_site_id')
            ->references('id')->on('swm.landfill_sites')->onDelete('cascade');
            $table->integer('type_of_waste');
            $table->float('volume');
            $table->timestamp('date_time');
            $table->boolean('verification');
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
        Schema::dropIfExists('swm.transfer_log_outs');
    }
}
