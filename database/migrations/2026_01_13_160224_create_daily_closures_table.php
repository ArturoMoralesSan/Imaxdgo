<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyClosuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_closures', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('branch_id');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->date('date');
            $table->decimal('cash_total', 15, 6)->default(0);
            $table->decimal('card_total', 15, 6)->default(0);
            $table->decimal('transfer_total', 15, 6)->default(0);
            $table->decimal('expenses', 15, 6)->default(0);
            $table->decimal('total', 15, 6)->default(0);
            $table->timestamp('closed_at');
            $table->unique(['branch_id', 'date']);
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
        Schema::dropIfExists('daily_closures');
    }
}
