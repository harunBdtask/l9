<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDyeingFinishingProductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dyeing_finishing_productions', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id', 50)->nullable();
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('buyer_id');
            $table->enum('entry_basis', [1, 2]);
            $table->unsignedInteger('textile_order_id')->nullable();
            $table->string('textile_order_no', 40)->nullable();
            $table->unsignedInteger('dyeing_batch_id')->nullable();
            $table->string('dyeing_batch_no', 40)->nullable();
            $table->unsignedInteger('sub_dyeing_unit_id')->nullable();
            $table->date('production_date')->nullable();
            $table->unsignedInteger('machine_id')->nullable();
            $table->dateTime('loading_date')->nullable();
            $table->dateTime('unloading_date')->nullable();
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
        Schema::dropIfExists('dyeing_finishing_productions');
    }
}
