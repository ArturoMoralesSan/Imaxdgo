<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRaceRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('race_registrations', function (Blueprint $table) {
            $table->increments();
            $table->unsignedInteger('race_id');
            $table->string('folio');
            $table->string('name');
            $table->string('age');
            $table->string('sex');
            $table->string('size');
            $table->string('cel');
            $table->string('km');
            $table->foreign('race_id')>references('id')->on('races')->onDelete('cascade');
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
        Schema::dropIfExists('race_registrations');
    }
}
