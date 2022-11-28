<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateYarnRequisitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yarn_requisitions', function (Blueprint $table) {
            $table->dropColumn('supplier_id');
            $table->dropColumn('yarn_type_id');
            $table->dropColumn('yarn_count_id');
            $table->dropColumn('yarn_composition_id');

            $table->dropColumn('yarn_lot');
            $table->dropColumn('requisition_qty');
            $table->dropColumn('requisition_date');
            $table->dropColumn('remarks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('yarn_requisitions', function (Blueprint $table) {
            $table->unsignedInteger('supplier_id')->nullable()->after('program_id');
            $table->unsignedInteger('yarn_type_id')->nullable()->after('supplier_id');
            $table->unsignedInteger('yarn_count_id')->nullable()->after('yarn_type_id');
            $table->unsignedInteger('yarn_composition_id')->nullable()->after('yarn_count_id');
            $table->string('yarn_lot')->nullable()->after('yarn_composition_id');
            $table->string('requisition_qty')->nullable()->after('yarn_lot');
            $table->date('requisition_date')->nullable()->after('requisition_qty');
            $table->string('remarks')->nullable()->after('requisition_date');
        });
    }
}
