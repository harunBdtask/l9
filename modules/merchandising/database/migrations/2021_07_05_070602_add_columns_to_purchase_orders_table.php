<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToPurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->string('ready_to_approved')->default(0)->after('po_no')
                ->comment("1=Yes,0=No");
            $table->string('is_approved')->default(0)->after('ready_to_approved')->comment("1=Yes,0=No");;
            $table->text('un_approve_request')->nullable()->after('is_approved');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn('ready_to_approved');
            $table->dropColumn('is_approved');
            $table->dropColumn('un_approve_request');
        });
    }
}
