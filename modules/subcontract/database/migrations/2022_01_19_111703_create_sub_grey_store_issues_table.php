<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubGreyStoreIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_grey_store_issues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('factory_id');
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('sub_textile_order_id')->nullable();
            $table->unsignedBigInteger('sub_grey_store_id');
            $table->unsignedBigInteger('sub_dyeing_unit_id')->nullable();
            $table->string('challan_no', 40);
            $table->date('challan_date')->nullable();
            $table->string('issue_purpose')->nullable();
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
        Schema::dropIfExists('sub_grey_store_issues');
    }
}
