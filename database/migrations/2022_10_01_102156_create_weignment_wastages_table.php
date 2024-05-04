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
        Schema::create('weignment_wastages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('weignment');
            $table->unsignedBigInteger('waste');
            $table->double('weight');
            $table->foreign('weignment')->references('id')->on('weignments')->onDelete('cascade');
            $table->foreign('waste')->references('id')->on('waste_types');
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
        Schema::dropIfExists('weignment_wastages');
    }
};
