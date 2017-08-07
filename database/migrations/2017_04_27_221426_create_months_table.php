<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('months')) {
            Schema::create('months', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('house_rent')->nullable();
                $table->integer('water_works')->nullable();
                $table->integer('gas')->nullable();
                $table->integer('electrical')->nullable();
                $table->integer('mobile_phone')->nullable();
                $table->integer('saving')->nullable();
                $table->integer('loan')->nullable();
                $table->integer('insurance')->nullable();
                $table->integer('credit_card')->nullable();
                $table->string('user')->nullable();
                $table->datetime('created_at')->nullable();
                $table->datetime('updated_at')->nullable();

                // 外部キーを追加 ※daysテーブルを作る前にdaysのmonth_idとリレーションを組んでもエラーになる。
                // 外部キー設定をするならテーブルを作る順番も大切。
                // 外部キーのことをよく理解していないので、一旦コメントアウトしてリリースする
//                $table->foreign('id')
//                    ->references('month_id')
//                    ->on('days')
//                    ->onDelete('cascade');
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
        Schema::dropIfExists('months');
    }
}