<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSwmPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE SCHEMA IF NOT EXISTS swmpayment_info");
        Schema::create('swmpayment_info.swm_payments', function (Blueprint $table) {
            $table->id();
            $table->string('tax_code',50);
            $table->string('owner_name',100);
            $table->string('owner_gender',50)->nullable();
            $table->bigInteger('owner_contact')->nullable();
            $table->date('last_payment_date');
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
        Schema::dropIfExists('swm_payments');
    }
}
