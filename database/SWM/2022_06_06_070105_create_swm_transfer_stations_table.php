<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSwmTransferStationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('swm.transfer_stations', function (Blueprint $table) {
            $table->id();
            $table->string('name',50);
            $table->index('name');
            $table->integer('ward'); //foreign keyyy??? from wards
            $table->boolean('separation_facility');
            $table->float('area');
            $table->float('capacity');
            $table->geometry('geom');
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
        Schema::dropIfExists('swm.transfer_stations');
    }
}
