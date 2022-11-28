<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubGreyStoreDailyStockSummaryReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_grey_store_daily_stock_summary_reports', function (Blueprint $table) {
            $table->id();
            $table->date('production_date');
            $table->unsignedBigInteger('factory_id');
            $table->unsignedBigInteger('supplier_id')->comment('party_type=Dyeing/Finishing Supplier');
            $table->unsignedBigInteger('sub_grey_store_id');
            $table->unsignedBigInteger('sub_textile_operation_id');
            $table->unsignedBigInteger('fabric_composition_id')->nullable();
            $table->unsignedBigInteger('fabric_type_id')->nullable();
            $table->unsignedBigInteger('color_id')->nullable();
            $table->string('ld_no')->nullable();
            $table->unsignedBigInteger('color_type_id')->nullable();
            $table->string('finish_dia')->nullable();
            $table->unsignedBigInteger('dia_type_id')->nullable()->comment('1=Open,2=Tubular,3=Needle Open');
            $table->string('gsm')->nullable();
            $table->text('material_description')->nullable();
            $table->string('receive_qty')->nullable();
            $table->string('receive_return_qty')->nullable();
            $table->string('issue_qty')->nullable();
            $table->string('issue_return_qty')->nullable();
            $table->unsignedBigInteger('unit_of_measurement_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('sub_grey_store_daily_stock_summary_reports');
    }
}
