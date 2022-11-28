<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunmsToProcurementRequistionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('procurement_requisitions', function (Blueprint $table) {
            $table->date('required_date')->nullable()->after('date');
            $table->unsignedInteger('department_head')->nullable()->after('department_id');
            $table->tinyInteger('priority')->nullable()->after('department_head');
            $table->unsignedInteger('project_id')->nullable()->change();
            $table->unsignedInteger('department_id')->nullable()->change();
            $table->unsignedInteger('unit_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('procurement_requisitions', function (Blueprint $table) {
            $table->dropColumn('required_date');
            $table->dropColumn('department_head');
            $table->dropColumn('priority');

            $table->unsignedInteger('project_id')->nullable(false)->change();
            $table->unsignedInteger('department_id')->nullable(false)->change();
            $table->unsignedInteger('unit_id')->nullable(false)->change();
        });
    }
}
