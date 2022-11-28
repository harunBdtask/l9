<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrimsAccessoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trims_accessories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('budget_master_id');
            $table->unsignedInteger('supplier_id')->nullable();
            $table->string('attention_name', 40)->nullable();
            $table->unsignedInteger('item_id')->nullable();
            $table->smallInteger('break_down_type')->default(0)->comment('0=Color wise,1=Single color single size,2=All color all size,3=Size wise percentage,4=Additional');
            $table->integer('general_percentage')->nullable();
            $table->unsignedInteger('unit_of_measurement_id')->nullable();
            $table->double('thread_consumption', 12, 4)->nullable();
            $table->double('cone_meter', 12, 4)->nullable();
            $table->double('all_color_all_size_unit_price', 12, 4)->nullable();
            $table->string('quality')->nullable();
            $table->date('delivery_date')->nullable();
            $table->string('wash_note', 40)->nullable();
            $table->string('order_note', 40)->nullable();
            $table->unsignedInteger('delivery_factory_id')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->unsignedInteger('factory_id')->nullable();
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
        Schema::dropIfExists('trims_accessories');
    }
}
