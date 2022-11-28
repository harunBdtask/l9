<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBundleCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bundle_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bundle_no');
            $table->string('size_wise_bundle_no')->nullable();
            $table->integer('quantity');
            $table->unsignedInteger('buyer_id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedInteger('purchase_order_id');
            $table->unsignedInteger('color_id');
            $table->unsignedInteger('country_id')->nullable();
            $table->unsignedInteger('lot_id');
            $table->string('roll_no', 100)->nullable();
            $table->unsignedInteger('size_id');
            $table->string('suffix')->nullable();
            $table->string('serial');
            $table->boolean('sl_overflow')->nullable();
            $table->string('cutting_no')->nullable();
            $table->unsignedInteger('cutting_challan_no')->nullable();
            $table->boolean('cutting_challan_status')->default(false);
            $table->unsignedInteger('cutting_qc_challan_no')->nullable();
            $table->boolean('cutting_qc_challan_status')->default(false);
            $table->unsignedInteger('bundle_card_generation_detail_id');
            $table->smallInteger('replace')->nullable();
            $table->smallInteger('fabric_holes_small')->nullable();
            $table->smallInteger('fabric_holes_large')->nullable();
            $table->smallInteger('end_out')->nullable();
            $table->smallInteger('dirty_spot')->nullable();
            $table->smallInteger('oil_spot')->nullable();
            $table->smallInteger('colour_spot')->nullable();
            $table->smallInteger('lycra_missing')->nullable();
            $table->smallInteger('missing_yarn')->nullable();
            $table->string('yarn_contamination', 15)->nullable();
            $table->smallInteger('crease_mark')->nullable();
            $table->smallInteger('others')->nullable();
            $table->smallInteger('total_rejection')->default(0)->comment="cutting_rejection";
            $table->smallInteger('production_rejection_qty')->default(0)->comment="Print Factory Production";
            $table->smallInteger('qc_rejection_qty')->default(0)->comment="Print Factory Production";
            $table->smallInteger('print_rejection')->default(0);
            $table->smallInteger('embroidary_rejection')->default(0);
            $table->smallInteger('sewing_rejection')->default(0);
            $table->smallInteger('washing_rejection')->default(0);
            $table->smallInteger('print_factory_receive_rejection')->default(0)->comment='Print Factory Receive Short Qty';
            $table->smallInteger('print_factory_delivery_rejection')->default(0)->comment='Print Factory Delivery Short Qty';
            $table->smallInteger('status')->default(0);
            $table->smallInteger('qc_status')->default(0);
            $table->date('cutting_date')->nullable();
            $table->date('print_sent_date')->nullable();
            $table->date('print_received_date')->nullable();
            $table->date('embroidary_sent_date')->nullable();
            $table->date('embroidary_received_date')->nullable();
            $table->date('input_date')->nullable();
            $table->date('sewing_output_date')->nullable();
            $table->date('washing_date')->nullable();
            $table->unsignedInteger('cutting_table_id');
            $table->unsignedInteger('cutting_floor_id');
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['bundle_card_generation_detail_id', 'buyer_id']);
            $table->index(['cutting_no', 'cutting_floor_id', 'order_id']);
            $table->index(['lot_id', 'cutting_table_id', 'size_id']);

            $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('cascade');
            $table->foreign('color_id')->references('id')->on('colors')->onDelete('cascade');
            $table->foreign('cutting_table_id')->references('id')->on('cutting_tables')->onDelete('cascade');
            $table->foreign('cutting_floor_id')->references('id')->on('cutting_floors')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->foreign('lot_id')->references('id')->on('lots')->onDelete('cascade');
            $table->foreign('size_id')->references('id')->on('sizes')->onDelete('cascade');
            $table->foreign('bundle_card_generation_detail_id')->references('id')->on('bundle_card_generation_details')->onDelete('cascade');
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
        Schema::dropIfExists('bundle_cards');
    }
}
