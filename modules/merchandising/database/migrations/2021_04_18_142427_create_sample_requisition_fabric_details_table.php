<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSampleRequisitionFabricDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sample_requisition_fabric_details', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('requisition_id');
            $table->unsignedInteger('sample_id');
            $table->unsignedInteger('gmts_item_id');
            $table->unsignedInteger('body_part_id');
            $table->unsignedInteger('body_part_type');
            $table->unsignedInteger('fabric_nature_id');
            $table->unsignedInteger('color_type_id');
            $table->unsignedInteger('fabric_description_id');
            $table->unsignedInteger('fabric_source_id');
            $table->unsignedInteger('dia_type_id');

            $table->string('gsm')->nullable();

            $table->json('gmts_colors_id');

            $table->tinyInteger('sensitivity')->nullable();
            $table->unsignedInteger('uom_id');

            $table->decimal('req_qty', 8, 2);
            $table->decimal('total_qty', 8, 2);
            $table->decimal('total_amount', 8, 2);

            $table->string('img_src')->nullable();
            $table->string('remarks')->nullable();

            $table->json('details');
            $table->json('calculation');

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
        Schema::dropIfExists('sample_requisition_fabric_details');
    }
}
