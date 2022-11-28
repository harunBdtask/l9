<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToUserWiseBuyerPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_wise_buyer_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('view_buyer_id')->nullable();
            $table->string('permission_type')->comment('1=BuyerPermission,2=ViewBuyerPermission');
            $table->string('buyer_permission_type')->nullable();
            $table->string('view_buyer_permission_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_wise_buyer_permissions', function (Blueprint $table) {
            $table->dropColumn('view_buyer_id');
            $table->dropColumn('buyer_permission_type');
            $table->dropColumn('view_buyer_permission_type');
        });
    }
}
