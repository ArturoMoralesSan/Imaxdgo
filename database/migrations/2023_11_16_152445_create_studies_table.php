<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('studies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('key_name')->unique();
            $table->timestamps();
        });

        Schema::create('service_study', function (Blueprint $table) {
            $table->unsignedInteger('service_id');
            $table->unsignedInteger('study_id');

            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('study_id')->references('id')->on('studies')->onDelete('cascade');        });
    }

    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('studies');
        Schema::dropIfExists('service_study');

    }
}
