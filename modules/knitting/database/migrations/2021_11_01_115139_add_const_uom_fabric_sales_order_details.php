<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConstUomFabricSalesOrderDetails extends Migration
{

    protected function checkColumnSkip($column): bool
    {
        return !Schema::hasColumn('fabric_sales_order_details', $column);
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_sales_order_details', function (Blueprint $table) {
            if ($this->checkColumnSkip('cons_uom')) {
                $table->unsignedInteger('cons_uom')->nullable();
            }
        });
    }
}
