<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAddApprovalColumnsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('step')->nullable()->default(0);
            $table->string('is_approve')->nullable()->default(0)->comment('0=No, 1=Yes');
            $table->date('approve_date')->nullable();
            $table->string('ready_to_approved')->nullable()->default(0)->comment('0=No, 1=Yes');
            $table->string('un_approve_request')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'step',
                'is_approve',
                'approve_date',
                'ready_to_approved',
                'un_approve_request',
            ]);
        });
    }
}
