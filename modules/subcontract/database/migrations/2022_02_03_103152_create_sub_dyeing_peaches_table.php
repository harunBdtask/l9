<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubDyeingPeachesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_dyeing_peaches', function (Blueprint $table) {
            $table->id();
            $table->string('peach_uid')->nullable();
            $table->unsignedBigInteger('factory_id');
            $table->unsignedBigInteger('supplier_id');
            $table->enum('entry_basis', [1, 2])->comment('1=Sub Dyeing Batch, 2=Sub Dyeing Order');
            $table->unsignedInteger('sub_dyeing_batch_id')->nullable();
            $table->string('sub_dyeing_batch_no', 40)->nullable();
            $table->unsignedInteger('sub_textile_order_id')->nullable();
            $table->string('sub_textile_order_no', 40)->nullable();
            $table->date('production_date')->nullable();
            $table->string('before_dia', 50)->nullable();
            $table->string('before_gsm', 50)->nullable();
            $table->unsignedInteger('sub_dyeing_unit_id');
            $table->string('after_dia', 50)->nullable();
            $table->string('after_gsm', 50)->nullable();
            $table->unsignedInteger('shift_id')->nullable();
            $table->string('drum_speed', 50)->nullable();
            $table->unsignedInteger('dyeing_machine_id')->nullable();
            $table->string('roller_speed')->nullable();
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
        Schema::dropIfExists('sub_dyeing_peaches');
    }
}
