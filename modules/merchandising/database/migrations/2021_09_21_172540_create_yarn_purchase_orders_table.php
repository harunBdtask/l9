<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYarnPurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yarn_purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('wo_no', 30)->nullable();
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('buyer_id');
            $table->unsignedInteger('supplier_id');
            $table->date('wo_date');
            $table->date('delivery_date');
            $table->unsignedInteger('pay_mode');
            $table->unsignedInteger('source')->nullable();
            $table->string('currency')->nullable();
            $table->integer('wo_basis')->nullable()->comment("1: REQ. Basic, 2: Style Basic, 3: PO Basic , 4: Independent Basic");
            $table->string('pay_term')->nullable();
            $table->unsignedInteger('incoterm_id')->nullable();
            $table->string('tenor')->nullable();
            $table->text('attention')->nullable();
            $table->text('remarks')->nullable();

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
        Schema::dropIfExists('yarn_purchase_orders');
    }
}
