<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DyesChemicalTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dyes_chemical_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('category_id')->nullable();
            $table->unsignedInteger('brand_id')->nullable();
            $table->float('qty')->nullable();
            $table->float('rate')->nullable();
            $table->date('trn_date')->nullable();
            $table->string('trn_type', 10)->index();
            $table->foreignId('dyes_chemical_receive_id')->constrained('dyes_chemicals_receive', 'id')->cascadeOnDelete();
            $table->foreignId('receive_id')->nullable()->constrained('dyes_chemical_transactions', 'id')->cascadeOnDelete();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('dyes_chemical_transactions');
    }
}
