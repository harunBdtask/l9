<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManualDateWiseSewingReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manual_date_wise_sewing_reports', function (Blueprint $table) {
            $table->id();
            $table->date('production_date');
            $table->unsignedBigInteger('factory_id')->index();
            $table->unsignedBigInteger('subcontract_factory_id')->nullable()->index();
            $table->unsignedBigInteger('buyer_id')->index();
            $table->unsignedBigInteger('order_id')->index();
            $table->unsignedBigInteger('garments_item_id')->index();
            $table->unsignedBigInteger('purchase_order_id')->index();
            $table->unsignedBigInteger('color_id')->nullable()->index();
            $table->unsignedBigInteger('size_id')->nullable()->index();
            $table->unsignedBigInteger('floor_id')->nullable()->index();
            $table->unsignedBigInteger('line_id')->nullable()->index();
            $table->unsignedBigInteger('sub_sewing_floor_id')->nullable()->index();
            $table->unsignedBigInteger('sub_sewing_line_id')->nullable()->index();
            $table->integer('input_qty')->default(0);
            $table->integer('sewing_output_qty')->default(0);
            $table->integer('sewing_rejection_qty')->default(0);
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
        Schema::dropIfExists('manual_date_wise_sewing_reports');
    }
}
