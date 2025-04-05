<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('swm.collection_points', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('route_id');
            $table->foreign('route_id')
            ->references('id')->on('swm.routes')->onDelete('cascade');
            $table->string('type');
            $table->index('type');
            $table->float('capacity');
            $table->integer('ward');
            $table->string('service_type');
            $table->integer('household_served');
            $table->string('status');
            $table->time('collection_time');
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
        Schema::dropIfExists('swm.collection_points');
    }
}
