<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYarnGatePassChallanScanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yarn_gate_pass_challan_scan', function (Blueprint $table) {
            $table->id();
            $table->string('challan_no')->nullable();
            $table->string('challan_date')->nullable();
            $table->string('supplier_id')->nullable();
            $table->string('gate_pass_no')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->string('lock_no')->nullable();
            $table->string('driver_name')->nullable();
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
        Schema::dropIfExists('yarn_gate_pass_challan_scan');
    }
}
