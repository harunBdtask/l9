<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBfFundRequisitionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bf_fund_requisition_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requisition_id');
            $table->date('date');
            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('item_id');
            $table->text('item_description')->nullable();
            $table->unsignedBigInteger('uom');
            $table->integer('existing_qty');
            $table->integer('req_qty');
            $table->decimal('rate', 15, 2);
            $table->decimal('amount', 15, 2);
            $table->text('remarks')->nullable();
            $table->tinyInteger('approval_status')->default(0)
                ->comment('Audit 0=unapproved, 1=approved, 3=account approved');

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
        Schema::dropIfExists('bf_fund_requisition_details');
    }
}
