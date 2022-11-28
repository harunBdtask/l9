<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CostAssumption extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cost_assumptions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sample_ref_no');
            $table->unsignedInteger('buyer_id');
            $table->unsignedInteger('agent_id');
            $table->unsignedInteger('currency_id');
            $table->float('cost_per_set');
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
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
        Schema::drop('cost_assumptions');
    }
}
