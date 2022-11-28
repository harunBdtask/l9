<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYarnPurchaseRequisitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yarn_purchase_requisitions', function (Blueprint $table) {
            $table->id();
            $table->string('requisition_no', 30);
            $table->unsignedInteger('factory_id');
            $table->date('required_date');
            $table->date('requisition_date');
            $table->unsignedInteger('pay_mode');
            $table->unsignedInteger('source')->nullable();
            $table->string('currency')->nullable();
            $table->unsignedInteger('dealing_merchant_id')->nullable();
            $table->text('attention')->nullable();
            $table->string('remarks')->nullable();

            $table->tinyInteger('ready_to_approve')->nullable();
            $table->text('unapproved_request')->nullable();
            $table->tinyInteger('is_approved')->default(0)->comment('1=Approved');

            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();

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
        Schema::dropIfExists('yarn_purchase_requisitions');
    }
}
