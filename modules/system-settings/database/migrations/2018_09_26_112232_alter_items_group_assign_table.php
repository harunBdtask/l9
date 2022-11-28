<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AlterItemsGroupAssignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items_group_assign', function ($table) {
            $table->unsignedInteger('item_id')->change();
            $table->unsignedInteger('item_group_id')->change();
            $table->unsignedInteger('factory_id')->change();
            $table->integer('status')->default(1)->comment = "1=Active,2=In Active,3=Cancelled";
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('is_deleted')->default(0)->comment = "0=Not Deleted,1=Deleted";
            $table->integer('deleted_by')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items_group_assign', function ($table) {
            $table->dropColumn('status');
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
            $table->dropColumn('is_deleted');
            $table->dropColumn('deleted_by');
        });
    }
}
