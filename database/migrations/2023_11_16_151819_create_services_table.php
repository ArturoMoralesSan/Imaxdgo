<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->increments('id');
            $table->string('folio')->nullable();
            $table->integer('last_rx')->nullable();
            $table->unsignedInteger('branch_id');
            $table->float('cost', 8, 2);
            $table->boolean('print');
            $table->integer('week');
            $table->string('patient');
            $table->string('month');
            $table->year('year');
            $table->unsignedInteger('payment_id');
            $table->integer('no_rx')->nullable();
            $table->date('date');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
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
        Schema::dropIfExists('services');
    }
}
