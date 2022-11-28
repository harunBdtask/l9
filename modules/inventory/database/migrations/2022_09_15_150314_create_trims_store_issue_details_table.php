<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrimsStoreIssueDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trims_store_issue_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trims_store_issue_id');
            $table->unsignedBigInteger('trims_store_bin_card_id');
            $table->unsignedBigInteger('trims_store_bin_card_detail_id');
            $table->unsignedBigInteger('factory_id');
            $table->unsignedBigInteger('store_id');
            $table->unsignedBigInteger('item_id');
            $table->text('item_description')->nullable();
            $table->date('current_date')->nullable();
            $table->date('issue_date')->nullable();
            $table->string('issue_qty')->nullable();;
            $table->string('mrr_qty')->nullable();
            $table->string('color_id')->nullable();
            $table->string('size_id')->nullable();
            $table->string('size')->nullable();
            $table->string('issue_return_qty')->nullable();
            $table->string('issue_return_date')->nullable();
            $table->unsignedBigInteger('uom_id')->nullable();
            $table->string('approval_shade_code')->nullable();
            $table->string('booking_qty')->nullable();
            $table->string('issue_to')->nullable();
            $table->unsignedBigInteger('floor_id')->nullable();
            $table->unsignedBigInteger('room_id')->nullable();
            $table->unsignedBigInteger('rack_id')->nullable();
            $table->unsignedBigInteger('shelf_id')->nullable();
            $table->unsignedBigInteger('bin_id')->nullable();
            $table->string('remarks')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('trims_store_issue_details');
    }
}
