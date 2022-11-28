<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportLcChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_lc_charges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('b_to_b_margin_lc_id');
            $table->date('pay_date')->nullable();
            $table->tinyInteger('pay_head_id')
                ->nullable()
                ->comment("1=Bank Commission, 2=Vat On Bank Commission, 3=Insurance Coverage, 4=Add Confirmation Change");
            $table->tinyInteger('charge_for_id')->nullable()->comment("1=LC Opening, 2=LC Amendments");
            $table->string('amount', 30)->nullable();
            $table->unsignedInteger('factory_id')->nullable();
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
        Schema::dropIfExists('import_lc_charges');
    }
}
