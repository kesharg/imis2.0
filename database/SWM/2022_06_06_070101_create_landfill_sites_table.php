<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandfillSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE SCHEMA IF NOT EXISTS swm");
        DB::statement("CREATE EXTENSION IF NOT EXISTS postgis");
        Schema::create('swm.landfill_sites', function (Blueprint $table) {
            $table->id();
            $table->string('name',50);
            $table->index('name');
            $table->integer('ward'); //foreign keyyy??? from wards
            $table->float('area');
            $table->float('capacity');
            $table->date('life_span');
            $table->string('status',50);
            $table->geometry('geom');
            $table->string('operated_by',50);
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
        Schema::dropIfExists('swm.landfill_sites');
    }
}
