<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraForChallanYarnIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yarn_issues', function (Blueprint $table) {
            $table->string('lock_no')->nullable()->after('challan_no');
            $table->string('driver_name')->nullable()->after('challan_no');
            $table->string('vehicle_type')->nullable()->after('challan_no');
            $table->string('gate_pass_no')->nullable()->after('challan_no');
            $table->string('vehicle_number')->nullable()->after('challan_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropColumns('yarn_issues',[
            'lock_no',
            'driver_name',
            'gate_pass_no',
            'vehicle_type',
            'vehicle_number'
        ]);
    }
}
