<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('days')) {
            Schema::create('days', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('month_id')->unsigned();
                $table->integer('day')->unsigned();
                $table->integer('food')->nullable();
                $table->integer('clothes')->nullable();
                $table->integer('medical')->nullable();
                $table->integer('traffic')->nullable();
                $table->integer('social_expenses')->nullable();
                $table->integer('recreation')->nullable();
                $table->text('note')->nullable();
                $table->string('user')->nullable();
                $table->datetime('created_at')->nullable();
                $table->datetime('updated_at')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('days');
    }
}