<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubDyeingFinishingProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_dyeing_finishing_productions', function (Blueprint $table) {
            $table->id();
            $table->string('production_uid', 50)->nullable();
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('supplier_id');
            $table->enum('entry_basis', [1, 2]);
            $table->unsignedInteger('sub_dyeing_batch_id')->nullable();
            $table->string('sub_dyeing_batch_no', 40)->nullable();
            $table->unsignedInteger('sub_textile_order_id')->nullable();
            $table->string('sub_textile_order_no', 40)->nullable();
            $table->unsignedInteger('sub_dyeing_unit_id')->nullable();
            $table->date('production_date')->nullable();
            $table->unsignedInteger('machine_id')->nullable();
            $table->date('loading_date')->nullable();
            $table->date('unloading_date')->nullable();
            $table->unsignedInteger('shift_id')->nullable();
            $table->string('length_shrinkage')->nullable();
            $table->string('width_shrinkage')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('sub_dyeing_finishing_productions');
    }
}
