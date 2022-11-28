<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateFabricSalesOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    protected function dropColumnHas($column){
        if (Schema::hasColumn('fabric_sales_orders',$column)){
            Schema::table('fabric_sales_orders', function (Blueprint $table) use ($column){
                $table->dropColumn($column);
            });
        }
    }
    protected function checkColumnSkip($column){
       return !Schema::hasColumn('fabric_sales_orders', $column);
    }
    public function up()
    {
        $this->dropColumnHas('booking_id');
        $this->dropColumnHas('unapprove_id');
        $this->dropColumnHas('team_leader_id');
        $this->dropColumnHas('dealing_merchant_id');

        Schema::table('fabric_sales_orders', function (Blueprint $table) {
            if ($this->checkColumnSkip('fabric_composition')){
                $table->string('fabric_composition')->after('style_name')->nullable();//new
            }
            if ($this->checkColumnSkip('team_leader')){
                $table->string('team_leader')->after('fabric_composition')->nullable();//new
            }
            if ($this->checkColumnSkip('unapproved_request')){
                $table->text('unapproved_request')->after('team_leader')->nullable();//new
            }
            if ($this->checkColumnSkip('dealing_merchant')){
                $table->string('dealing_merchant')->after('unapproved_request')->nullable();//new
            }
        });
    }
}
