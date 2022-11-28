<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserToPageWiseViewPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('page_wise_view_permissions', function (Blueprint $table) {
            $table->dropColumn('buyer_id');
            $table->unsignedInteger('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('page_wise_view_permissions', function (Blueprint $table) {
            $table->unsignedInteger('buyer_id')->nullable();
            $table->dropColumn('user_id');
        });
    }
}
