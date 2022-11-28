<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubGreyStoreIssueDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_grey_store_issue_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('factory_id');
            $table->unsignedBigInteger('sub_grey_store_issue_id');
            $table->unsignedBigInteger('supplier_id')->comment('party_type=Dyeing/Finishing Supplier');
            $table->unsignedBigInteger('sub_textile_order_id')->nullable();
            $table->unsignedBigInteger('sub_textile_order_detail_id')->nullable();
            $table->unsignedBigInteger('sub_grey_store_id');
            $table->unsignedBigInteger('sub_dyeing_unit_id')->nullable();
            $table->string('challan_no', 40)->nullable();
            $table->date('challan_date')->nullable();
            $table->unsignedBigInteger('sub_textile_operation_id')->nullable();
            $table->unsignedBigInteger('sub_textile_process_id')->nullable();
            $table->unsignedBigInteger('fabric_composition_id')->nullable()->comment('new_fabric_compositions table id');
            $table->unsignedBigInteger('fabric_type_id')->nullable()->comment('composition_types table id');
            $table->unsignedBigInteger('color_id')->nullable();
            $table->string('ld_no')->nullable();
            $table->unsignedBigInteger('color_type_id')->nullable();
            $table->string('finish_dia')->nullable();
            $table->tinyInteger('dia_type_id')->nullable()->comment('1=Open,2=Tubular,3=Needle Open');
            $table->string('gsm')->nullable();
            $table->text('fabric_description')->nullable()->comment('fabric_composition + fabric_type +color + ld_no + color_type + finish_dia + dia_type + gsm');
            $table->json('yarn_details')->nullable();
            $table->string('grey_required_qty')->nullable();
            $table->unsignedBigInteger('unit_of_measurement_id')->nullable();
            $table->string('total_roll')->nullable();
            $table->string('issue_qty')->nullable();
            $table->string('return_roll')->nullable();
            $table->string('issue_return_qty')->nullable();
            $table->string('total_batch_assigned_qty')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('sub_grey_store_issue_details');
    }
}
