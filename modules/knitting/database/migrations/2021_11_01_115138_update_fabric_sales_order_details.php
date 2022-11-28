<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFabricSalesOrderDetails extends Migration
{

    protected function dropColumnHas($column)
    {
        if (Schema::hasColumn('fabric_sales_order_details', $column)) {
            Schema::table('fabric_sales_order_details', function (Blueprint $table) use ($column) {
                $table->dropColumn($column);
            });
        }
    }

    protected function checkColumnSkip($column)
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
        $this->dropColumnHas('color');
        $this->dropColumnHas('amount');
        $this->dropColumnHas('process');
        $this->dropColumnHas('average_price');
        Schema::table('fabric_sales_order_details', function (Blueprint $table) {
            if ($this->checkColumnSkip('item_color')) {
                $table->string('item_color');//new
            }
            if ($this->checkColumnSkip('gmt_color_id')) {
                $table->string('gmt_color_id');//new
            }
            if ($this->checkColumnSkip('gmt_color')) {
                $table->unsignedInteger('gmt_color');//new
            }
            if ($this->checkColumnSkip('amount')) {
                $table->string('amount', 30);//change data type
            }
            if ($this->checkColumnSkip('average_price')) {
                $table->string('average_price', 30);//change data type
            }
            if ($this->checkColumnSkip('process_id')) {
                $table->unsignedInteger('process_id')->nullable();//new
            }
            if ($this->checkColumnSkip('item_color_id')) {
                $table->unsignedInteger('item_color_id');//new
            }
            if ($this->checkColumnSkip('fabric_nature_id')) {
                $table->unsignedInteger('fabric_nature_id')->nullable();//new
            }
            if ($this->checkColumnSkip('fabric_nature')) {
                $table->string('fabric_nature')->nullable();//new
            }
            if ($this->checkColumnSkip('color_range_id')) {
                $table->unsignedInteger('color_range_id')->nullable();//new
            }
        });
    }
}
