<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnsToMerGatePassChallansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('mer_gate_pass_challans', 'vehicle_no')) {
            Schema::table('mer_gate_pass_challans', function (Blueprint $table) {
                $table->string('vehicle_no')->after('goods_details')->nullable();
            });
        }
        if (!Schema::hasColumn('mer_gate_pass_challans', 'driver_name')) {
            Schema::table('mer_gate_pass_challans', function (Blueprint $table) {
                $table->string('driver_name')->after('vehicle_no')->nullable();
            });
        }
        if (!Schema::hasColumn('mer_gate_pass_challans', 'lock_no')) {
            Schema::table('mer_gate_pass_challans', function (Blueprint $table) {
                $table->string('lock_no')->after('driver_name')->nullable();
            });
        }
        if (!Schema::hasColumn('mer_gate_pass_challans', 'bag_quantity')) {
            Schema::table('mer_gate_pass_challans', function (Blueprint $table) {
                $table->string('bag_quantity')->after('lock_no')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mer_gate_pass_challans', function (Blueprint $table) {
            $table->dropColumn('vehicle_no');
            $table->dropColumn('driver_name');
            $table->dropColumn('lock_no');
            $table->dropColumn('bag_quantity');
        });
    }
}
