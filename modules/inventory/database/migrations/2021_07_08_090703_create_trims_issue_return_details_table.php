<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrimsIssueReturnDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trims_issue_return_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trims_issue_return_id');
            $table->unsignedBigInteger('trims_issue_details_id');
            $table->string('uniq_id');
            $table->string('style_name', 30);
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('uom_id');
            $table->text('item_description');
            $table->string('item_color', 30);
            $table->string('item_size');
            $table->string('floor');
            $table->string('room');
            $table->string('rack');
            $table->string('shelf');
            $table->string('bin');
            $table->string('buyer_order');
            $table->json('po_no');
            $table->date('shipment_date');
            $table->integer('po_qty');
            $table->integer('return_qty');
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
        Schema::dropIfExists('trims_issue_return_details');
    }
}
