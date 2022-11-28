<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAndAddColumnsInHrEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hr_employees', function (Blueprint $table) {
            $table->renameColumn('post_code', 'post_code_id');
            $table->renameColumn('present_address_post_code', 'present_address_post_code_id');
            $table->string('post_office_id')->after('upazilla_bn_id')->nullable();
            $table->string('post_office_bn')->after('post_office_id')->nullable();
            $table->string('present_address_post_office_id')->after('present_address_upazilla_bn_id')->nullable();
            $table->string('present_address_post_office_bn')->after('present_address_post_office_id')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hr_employees', function (Blueprint $table) {
            $table->renameColumn('post_code_id', 'post_code');
            $table->renameColumn('present_address_post_code_id', 'present_address_post_code');
            $table->dropColumn('post_office_id');
            $table->dropColumn('post_office_bn');
            $table->dropColumn('present_address_post_office_id');
            $table->dropColumn('present_address_post_office_bn');
        });
    }
}
