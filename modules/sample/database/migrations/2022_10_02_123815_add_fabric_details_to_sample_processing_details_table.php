<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFabricDetailsToSampleProcessingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sample_processing_details', function (Blueprint $table) {
            $table->json('fabric_details')->nullable()->after('calculations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sample_processing_details', function (Blueprint $table) {
            $table->dropColumn('fabric_details');
        });
    }
}
