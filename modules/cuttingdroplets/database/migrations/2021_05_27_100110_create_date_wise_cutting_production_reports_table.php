<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDateWiseCuttingProductionReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('date_wise_cutting_production_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->date('cutting_date');
            $table->unsignedInteger('cutting_floor_id')->nullable();
            $table->unsignedInteger('cutting_table_id')->nullable();
            $table->json('cutting_details')->nullable();
            $table->integer('total_cutting')->default(0);
            $table->integer('total_rejection')->default(0);
            $table->unsignedInteger('factory_id')->nullable();
            $table->timestamps();

            $table->index('cutting_date');
            $table->index('cutting_floor_id');
            $table->index('cutting_table_id');

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
        Schema::dropIfExists('date_wise_cutting_production_reports');
    }
}
