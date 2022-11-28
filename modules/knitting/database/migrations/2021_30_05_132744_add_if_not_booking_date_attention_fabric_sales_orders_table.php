<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIfNotBookingDateAttentionFabricSalesOrdersTable extends Migration
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
            if ($this->checkColumnSkip('booking_date')){
                $table->date('booking_date')->after('booking_type')->nullable();//missing on staging
            }
            if ($this->checkColumnSkip('attention')){
                $table->string('attention')->after('style_name')->nullable();//missing on staging
            }
        });
    }
}
