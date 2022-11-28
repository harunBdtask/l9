<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDateWiseHourlySewingProductionSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('date_wise_hourly_sewing_production_summaries', function (Blueprint $table) {
            $table->id();
            $table->date('production_date');
            $table->json('summary_data');
            $table->unsignedInteger('factory_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('date_wise_hourly_sewing_production_summaries');
    }
}
