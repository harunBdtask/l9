<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrderNoAndBatchNoTypeToDyeingFinishingProductionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dyeing_finishing_production_details', function (Blueprint $table) {
            $table->string('textile_order_no')->nullable()->change();
            $table->string('dyeing_batch_no')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dyeing_finishing_production_details', function (Blueprint $table) {
            $table->unsignedInteger('textile_order_no')->nullable()->change();
            $table->unsignedInteger('dyeing_batch_no')->nullable()->change();
        });
    }
}
