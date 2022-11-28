<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeYarnColorNullableYarnReceiveReturnDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yarn_receive_return_details', function (Blueprint $table) {
            DB::statement("ALTER TABLE `yarn_receive_return_details` CHANGE `yarn_color` `yarn_color` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('yarn_receive_return_details', function (Blueprint $table) {
            DB::statement("ALTER TABLE `yarn_receive_return_details` CHANGE `yarn_color` `yarn_color` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;");
        });
    }
}
