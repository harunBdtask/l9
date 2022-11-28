<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubDyeingTumblesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_dyeing_tumbles', function (Blueprint $table) {
            $table->id();
            $table->string('tumble_uid')->nullable();
            $table->unsignedBigInteger('factory_id');
            $table->unsignedBigInteger('supplier_id');
            $table->enum('entry_basis', [1, 2]);
            $table->unsignedInteger('sub_dyeing_batch_id')->nullable();
            $table->string('sub_dyeing_batch_no', 40)->nullable();
            $table->unsignedInteger('sub_textile_order_id')->nullable();
            $table->string('sub_textile_order_no', 40)->nullable();
            $table->date('production_date')->nullable();
            $table->date('streaming_date')->nullable();
            $table->unsignedInteger('shift_id')->nullable();
            $table->date('dry_date')->nullable();
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
        Schema::dropIfExists('sub_dyeing_tumbles');
    }
}
