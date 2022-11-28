<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManualEmblIssueProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manual_embl_issue_productions', function (Blueprint $table) {
            $table->id();
            $table->date('production_date');
            $table->tinyInteger('embl_name')->default(1)->comment('1=Printing, 2=Embroidery, 3=Wash, 4=Special Works, 5=Others');
            $table->string('embl_type')->nullable();
            $table->tinyInteger('source')->default(1)->comment('1=In House, 2=Out Bound');
            $table->unsignedInteger('factory_id');
            $table->unsignedBigInteger('subcontract_factory_id')->nullable();
            $table->unsignedInteger('buyer_id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('garments_item_id');
            $table->unsignedInteger('purchase_order_id');
            $table->unsignedInteger('color_id')->nullable();
            $table->unsignedInteger('size_id')->nullable();
            $table->unsignedInteger('sub_embl_floor_id')->nullable();
            $table->unsignedInteger('no_of_bags')->default(0);
            $table->unsignedInteger('production_qty')->default(0);
            $table->string('challan_no', 40)->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('factory_id');
            $table->index('production_date');
            $table->index(['buyer_id', 'order_id']);
            $table->index('purchase_order_id');
            $table->index('garments_item_id');
            $table->index(['color_id', 'size_id']);
            $table->index('challan_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manual_embl_issue_productions');
    }
}
