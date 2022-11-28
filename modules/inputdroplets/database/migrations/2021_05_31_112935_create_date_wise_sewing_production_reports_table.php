<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDateWiseSewingProductionReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('date_wise_sewing_production_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('floor_id');
            $table->unsignedInteger('line_id');
            $table->date('sewing_date');
            $table->json('sewing_details')->nullable();
            $table->integer('total_sewing_input')->default(0);
            $table->integer('total_sewing_output')->default(0);
            $table->integer('total_sewing_rejection')->default(0);
            $table->unsignedInteger('factory_id')->nullable();
            $table->timestamps();

            $table->index('floor_id');
            $table->index('line_id');
            $table->index('sewing_date');

            $table->foreign('factory_id')->references('id')->on('factories')->onDelete('cascade');
            $table->foreign('floor_id')->references('id')->on('floors')->onDelete('cascade');
            $table->foreign('line_id')->references('id')->on('lines')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('date_wise_sewing_production_reports');
    }
}
