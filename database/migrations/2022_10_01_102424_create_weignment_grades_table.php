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
        Schema::create('weignment_grades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('weignment');
            $table->unsignedBigInteger('grade');
            $table->double('weight');
            $table->foreign('weignment')->references('id')->on('weignments')->onDelete('cascade');
            $table->foreign('grade')->references('id')->on('grades');
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
        Schema::dropIfExists('weignment_grades');
    }
};
