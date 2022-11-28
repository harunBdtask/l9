<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCuttingTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cutting_targets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cutting_floor_id');
            $table->unsignedInteger('cutting_table_id');
            $table->smallInteger('mp')->nullable();
            $table->smallInteger('wh')->nullable();
            $table->integer('adding')->nullable();
            $table->integer('sub')->nullable();
            $table->integer('npt')->nullable();
            $table->date('target_date')->nullable();
            $table->integer('target')->default(0);
            $table->integer('status')->nullable();
            $table->unsignedInteger('factory_id');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['cutting_floor_id', 'cutting_table_id']);
            $table->foreign('cutting_floor_id')->references('id')->on('cutting_floors')->onDelete('cascade');
            $table->foreign('cutting_table_id')->references('id')->on('cutting_tables')->onDelete('cascade');
            $table->foreign('factory_id')->references('id')->on('factories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cutting_targets');
    }
}
