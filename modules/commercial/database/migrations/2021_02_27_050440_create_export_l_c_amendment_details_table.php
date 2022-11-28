<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportLCAmendmentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('export_lc_amendment_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('amendment_id');
            $table->unsignedInteger('export_lc_id');
            $table->unsignedInteger('po_id');
            $table->unsignedInteger('order_id');
            $table->double('attach_qty', 10, 2);
            $table->double('rate', 10, 2);
            $table->double('attach_value', 10, 2);
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
        Schema::dropIfExists('export_lc_amendment_details');
    }
}
