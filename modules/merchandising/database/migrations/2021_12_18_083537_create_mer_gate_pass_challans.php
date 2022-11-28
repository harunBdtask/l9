<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerGatePassChallans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mer_gate_pass_challans', function (Blueprint $table) {
            $table->id();
            $table->string('challan_no');
            $table->date('challan_date');
            $table->unsignedInteger('department_id');
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('merchant_id')->comment('users id');
            $table->unsignedInteger('supplier_id');
            $table->tinyInteger('good_id')->comment('1=Sample, 2=Fabric, 3=Trims, 4=Yarn');
            $table->tinyInteger('status')->comment('1=Development,2=Confirm Order');
            $table->string('remarks')->nullable();
            $table->string('file')->nullable();
            $table->json('goods_details')->nullable();
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
        Schema::dropIfExists('mer_gate_pass_challans');
    }
}
