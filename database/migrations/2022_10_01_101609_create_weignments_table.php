<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weignments', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('vehicle');
            $table->unsignedBigInteger('company');
            $table->unsignedBigInteger('shed');
            $table->unsignedBigInteger('farmer');
            $table->unsignedBigInteger('created_by');
            $table->double('gross_weight');
            $table->dateTime('weignment_date');
            // $table->foreign('vehicle')->references('id')->on('vehicles');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('shed')->references('id')->on('sheds');
            $table->foreign('company')->references('id')->on('company');
            $table->foreign('farmer')->references('id')->on('farmers');
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
        Schema::dropIfExists('weignments');
    }
};
