<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGsInvVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gs_inv_vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('type', 5);
            $table->date('trn_date');
            $table->json('details');
            $table->unsignedInteger('trn_with')->nullable();
            $table->string('store', 20);
            $table->unsignedInteger('requisition_id')->nullable();
            $table->boolean('readonly')->default(false);
            $table->string('voucher_no', 10)->nullable();
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
        Schema::dropIfExists('gs_inv_vouchers');
    }
}
