<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDateWiseWashingProductionReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('date_wise_washing_production_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->date('washing_date');
            $table->json('washing_details')->nullable();
            $table->integer('total_washing_sent')->default(0);
            $table->integer('total_washing_received')->default(0);
            $table->integer('total_washing_rejection')->default(0);
            $table->unsignedInteger('factory_id')->nullable();
            $table->timestamps();

            $table->index('washing_date');
            $table->index('factory_id');

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
        Schema::dropIfExists('date_wise_washing_production_reports');
    }
}
