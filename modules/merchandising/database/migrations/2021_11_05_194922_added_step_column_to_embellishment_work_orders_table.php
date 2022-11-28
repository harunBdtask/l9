<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddedStepColumnToEmbellishmentWorkOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('embellishment_work_orders', function (Blueprint $table) {
            $table->string('is_approved')->default(0)->comment('1=Approved');
            $table->tinyInteger('ready_to_approve')->nullable();
            $table->text('unapproved_request')->nullable();
            $table->integer('step')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('embellishment_work_orders', function (Blueprint $table) {
            $table->dropColumn('is_approved');
            $table->dropColumn('ready_to_approve');
            $table->dropColumn('unapproved_request');
            $table->dropColumn('step');
        });
    }
}
