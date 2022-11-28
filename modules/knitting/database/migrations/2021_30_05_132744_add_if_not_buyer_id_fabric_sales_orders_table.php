<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIfNotBuyerIdFabricSalesOrdersTable extends Migration
{

    protected function checkColumnSkip($column){
        return !Schema::hasColumn('fabric_sales_orders', $column);
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_sales_orders', function (Blueprint $table) {
            if ($this->checkColumnSkip('buyer_id')){
                $table->unsignedInteger('buyer_id')->after('style_name')->nullable();//missing on staging
            }
        });
    }
}
