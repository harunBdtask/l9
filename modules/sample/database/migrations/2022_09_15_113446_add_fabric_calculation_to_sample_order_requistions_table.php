<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFabricCalculationToSampleOrderRequistionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sample_order_requisitions', function (Blueprint $table) {
            $table->json('fabric_details_cal')->nullable()->after('requis_details_cal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sample_order_requisitions', function (Blueprint $table) {
            $table->dropColumn('fabric_details_cal');
        });
    }
}
