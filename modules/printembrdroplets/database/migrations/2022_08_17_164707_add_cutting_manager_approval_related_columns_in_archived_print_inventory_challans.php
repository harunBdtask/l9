<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCuttingManagerApprovalRelatedColumnsInArchivedPrintInventoryChallans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('archived_print_inventory_challans', function (Blueprint $table) {
            $table->tinyInteger('cut_manager_approval_steps')->default(0)->after('security_status');
            $table->tinyInteger('cut_manager_approval_status')->default(0)->comment("0=Not Approved,1=Approved")->after("cut_manager_approval_steps");
            $table->unsignedInteger('cut_manager_approved_id')->nullable()->after("cut_manager_approval_status");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('archived_print_inventory_challans', function (Blueprint $table) {
            $table->dropColumn([
                'cut_manager_approval_steps',
                'cut_manager_approval_status',
                'cut_manager_approved_id',
            ]);
        });
    }
}
