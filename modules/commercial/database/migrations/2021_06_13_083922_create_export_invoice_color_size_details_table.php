<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportInvoiceColorSizeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('export_invoice_color_size_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('export_invoice_detail_id');
            $table->unsignedInteger('order_id')->nullable();
            $table->unsignedInteger('po_id')->nullable();
            $table->unsignedInteger('garments_item_id')->nullable();
            $table->unsignedInteger('color_id')->nullable();
            $table->unsignedInteger('size_id')->nullable();
            $table->string('article_no')->nullable();
            $table->string('po_qty', 30)->nullable();
            $table->string('po_rate', 10)->nullable();
            $table->string('po_amount', 30)->nullable();
            $table->string('invoice_qty', 30)->nullable();
            $table->string('invoice_rate', 10)->nullable();
            $table->string('invoice_amount', 30)->nullable();
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
        Schema::dropIfExists('export_invoice_color_size_details');
    }
}
