<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('bank_accounts', function (Blueprint $table) {
//            $table->id();
//            $table->foreignId('factory_id');
//            $table->unsignedInteger('unit_id')->nullable();
//            $table->unsignedInteger('bank_id');
//            $table->date('date');
//            $table->string('branch_name', 30);
//            $table->string('contract_person', 30);
//            $table->string('contract_number', 30);
//            $table->string('contract_email', 30);
//            $table->unsignedInteger('control_account_id')->nullable();
//            $table->string('account_number', 40);
//            $table->unsignedInteger('currency_type_id');
//            $table->enum('status', [0, 1])->default(1)->comment('0 = Inactive, 1 = Active');
//            $table->unsignedInteger('created_by')->nullable();
//            $table->unsignedInteger('updated_by')->nullable();
//            $table->unsignedInteger('deleted_by')->nullable();
//            $table->timestamps();
//            $table->softDeletes();
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_accounts');
    }
}
