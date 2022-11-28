<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGsRequisitionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gs_requisition_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId("requisition_id")->constrained("gs_requisitions")->cascadeOnDelete();
            $table->foreignId("item")->constrained("gs_inv_items")->cascadeOnDelete();
            $table->text("item_description");
            $table->float("required_qty");
            $table->text("remarks");
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
        Schema::dropIfExists('gs_requisition_items');
    }
}
