<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTqmCuttingDhusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tqm_cutting_dhus', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('factory_id');
            $table->date('production_date');
            $table->unsignedInteger('cutting_floor_id');
            $table->unsignedInteger('cutting_table_id')->nullable();
            $table->unsignedInteger('buyer_id')->nullable();
            $table->unsignedInteger('order_id')->nullable();
            $table->unsignedInteger('purchase_order_id')->nullable();
            $table->integer('checked')->nullable();
            $table->integer('qc_pass')->nullable();
            $table->integer('total_defect')->nullable();
            $table->integer('reject')->nullable();
            $table->string('reason')->nullable();
            $table->float('dhu_level')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('tqm_cutting_dhus');
    }
}
