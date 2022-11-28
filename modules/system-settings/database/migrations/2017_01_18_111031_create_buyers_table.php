<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuyersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->string('short_name', 50);
            $table->unsignedInteger('country_id')->nullable();
            $table->unsignedInteger('factory_id');
            $table->string('contact_person')->nullable();
            $table->string('designation')->nullable();
            $table->string('exporters_ref')->nullable();
            $table->string('email')->nullable();
            $table->string('web_address')->nullable();
            $table->text('address_1')->nullable();
            $table->text('address_2')->nullable();
            $table->string('party_type')->nullable();
            $table->unsignedInteger('supplier_id')->nullable();
            $table->string('day_credit_limit')->nullable();
            $table->string('amount_credit_limit')->nullable();
            $table->unsignedInteger('currency_id')->nullable();
            $table->unsignedInteger('discount_method')->nullable();
            $table->string('security_deducted', 10)->nullable();
            $table->string('ait_deducted', 10)->nullable();
            $table->string('sewing_efficiency_marketing')->nullable();
            $table->string('sewing_efficiency_planing')->nullable();
            $table->string('team_name')->nullable();
            $table->string('status')->nullable();
            $table->string('buyer_code')->nullable();
            $table->string('remarks')->nullable();
            $table->string('link')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
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
        Schema::dropIfExists('buyers');
    }
}
