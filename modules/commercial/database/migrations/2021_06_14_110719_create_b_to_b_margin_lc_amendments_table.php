<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBToBMarginLcAmendmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b_to_b_margin_lc_amendments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('b_to_b_margin_lc_id');
            $table->unsignedInteger('amendment_no');
            $table->string('amendment_value', 30);
            $table->unsignedTinyInteger('value_changed_by');
            $table->date('last_shipment_date')->nullable();
            $table->date('lc_expiry_date')->nullable();
            $table->string('delivery_mode', 30)
                ->nullable()
                ->comment('sea, air, road, train, sea/air, road/air');

            $table->string('inco_term', 20)->nullable();
            $table->string('inco_term_place', 100)->nullable();

            $table->unsignedTinyInteger('partial_shipment')
                ->default(0)
                ->comment('0=No,1=Yes');

            $table->string('port_of_loading', 100)->nullable();
            $table->string('port_of_discharge', 100)->nullable();
            $table->string('pay_term', 40)
                ->nullable()
                ->comment('at_sight, usance, cash_in_advance, open_account');

            $table->string('tenor', 100)->nullable();
            $table->string('remarks')->nullable();
            $table->unsignedInteger('factory_id');

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('b_to_b_margin_lc_amendments');
    }
}
