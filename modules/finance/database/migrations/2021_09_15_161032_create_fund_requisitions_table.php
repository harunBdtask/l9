<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFundRequisitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fund_requisitions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('unit_id');
            $table->string('requisition_no');
            $table->date('requisition_date');
            $table->string('name')->nullable();
            $table->string('designation')->nullable();
            $table->date('expect_receive_date');
            $table->tinyInteger('is_approved')->default(0)->comment("0=Unapproved, 1=Approved");
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->unsignedInteger('factory_id')->default(0)->comment('0 for head office');
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
        Schema::dropIfExists('fund_requisitions');
    }
}
