<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManualInspectionProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manual_inspection_productions', function (Blueprint $table) {
            $table->id();
            $table->date('production_date');
            $table->tinyInteger('source')->default(1)->comment('1=In House, 2=Out Bound');
            $table->unsignedInteger('factory_id');
            $table->unsignedBigInteger('subcontract_factory_id')->nullable();
            $table->unsignedInteger('buyer_id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('garments_item_id');
            $table->unsignedInteger('purchase_order_id');
            $table->unsignedInteger('production_qty')->default(0);
            $table->string('reason')->nullable();
            $table->string('responsible_person', 80)->nullable();
            $table->text('remarks')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=Passed, 2=Fail, 3=Re-Check');
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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manual_inspection_productions');
    }
}
