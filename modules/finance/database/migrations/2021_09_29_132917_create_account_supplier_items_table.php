<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountSupplierItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_supplier_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_supplier_id')->constrained('account_suppliers')->cascadeOnDelete();
            $table->unsignedInteger('item_group_id');
            $table->float('price_per_unit');
            $table->timestamps();
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
        Schema::dropIfExists('account_supplier_items');
    }
}
