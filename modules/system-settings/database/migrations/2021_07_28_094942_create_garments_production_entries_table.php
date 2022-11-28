<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGarmentsProductionEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('garments_production_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('factory_id');
            $table->tinyInteger('entry_method')->comment('1 => Order Wise, 2=> Color Wise, 3=> Size Wise');
            $table->tinyInteger('entry_type')->comment('1 => Manual, 2=> Automated');
            $table->softDeletes();
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
        Schema::dropIfExists('garments_production_entries');
    }
}
