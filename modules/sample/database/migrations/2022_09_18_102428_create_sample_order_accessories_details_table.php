<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSampleOrderAccessoriesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sample_order_accessories_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sample_order_requisition_id');
            $table->unsignedBigInteger('item_group_id')->nullable();
            $table->json('details')->nullable();
            $table->json('calculations')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('sample_order_requisitions', function (Blueprint $table) {
            $table->json('accessories_details_cal')->nullable()->after('fabric_details_cal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sample_order_accessories_details');

        Schema::table('sample_order_requisitions', function (Blueprint $table) {
            $table->dropColumn('accessories_details_cal');
        });
    }
}
