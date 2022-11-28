<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBfBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bf_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('account_id');
            $table->unsignedInteger('factory_id')->nullable();
            $table->unsignedInteger('bank_id')->nullable();
            $table->date('date')->nullable();
            $table->string('branch_name', 30)->nullable();
            $table->string('contract_person', 30)->nullable();
            $table->string('contract_number', 30)->nullable();
            $table->string('contract_email', 30)->nullable();
            $table->string('account_number', 40)->nullable();
            $table->unsignedInteger('currency_type_id')->nullable();
            $table->enum('status', [0, 1])->default(1)->comment('0 = Inactive, 1 = Active');
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
        Schema::dropIfExists('bf_bank_accounts');
    }
}
