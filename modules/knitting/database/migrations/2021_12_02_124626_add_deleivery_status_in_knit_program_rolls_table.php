<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeleiveryStatusInKnitProgramRollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('knit_program_rolls', function (Blueprint $table) {
            $table->tinyInteger('delivery_status')->default(0)->after('reject_roll_weight');
            $table->string('delivery_challan_no')->nullable()->after('delivery_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('knit_program_rolls', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_status',
                'delivery_challan_no',
            ]);
        });
    }
}
