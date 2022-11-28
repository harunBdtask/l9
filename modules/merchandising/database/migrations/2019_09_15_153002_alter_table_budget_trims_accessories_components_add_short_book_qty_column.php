<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableBudgetTrimsAccessoriesComponentsAddShortBookQtyColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('budget_trims_accessories_components', function (Blueprint $table) {
            $table->addColumn('float', 'short_book_qty')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('budget_trims_accessories_components', function (Blueprint $table) {
            $table->removeColumn('short_book_qty');
        });
    }
}
