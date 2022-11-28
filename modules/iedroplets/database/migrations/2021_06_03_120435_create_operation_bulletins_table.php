<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperationBulletinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operation_bulletins', function (Blueprint $table) {
            $table->increments('id');
            $table->date('prepared_date')->nullable();
            $table->date('input_date')->nullable();
            $table->unsignedInteger('floor_id');
            $table->unsignedInteger('line_id');
            $table->unsignedInteger('buyer_id');
            $table->unsignedBigInteger('order_id');
            $table->integer('proposed_target')->default(0);
            $table->string('sketch')->nullable();
            $table->string('pattern_status', 70)->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->unsignedInteger('factory_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('floor_id')->references('id')->on('floors')->onDelete('cascade');
            $table->foreign('line_id')->references('id')->on('lines')->onDelete('cascade');
            $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('factory_id')->references('id')->on('factories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operation_bulletins');
    }
}
