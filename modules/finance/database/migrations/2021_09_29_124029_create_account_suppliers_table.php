<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_suppliers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('control_account_id');
            $table->string('supplier_no')->nullable();
            $table->tinyInteger('group_company')->default(1)->comment('1 = Yes, 2 = No');
            $table->unsignedInteger('ledger_account_id')->nullable();
            $table->string('name');
            $table->string('ledger_account_name')->nullable();
            $table->string('sub_ledger_account_name')->nullable();
            $table->text('head_address')->nullable();
            $table->text('branch_address')->nullable();
            $table->json('contract_information')->nullable();
            $table->text('note')->nullable();
            $table->string('attachment')->nullable();
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
        Schema::dropIfExists('account_suppliers');
    }
}
