<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBfLoanAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bf_loan_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('factory_id')->nullable();
            $table->unsignedInteger('project_id')->nullable();
            $table->unsignedInteger('unit_id')->nullable();
            $table->unsignedInteger('bank_id')->nullable();
            $table->string('loan_type')->nullable();
            $table->string('mode_of_loan')->nullable();
            $table->unsignedInteger('control_account_id')->nullable();
            $table->string('loan_account_number')->nullable();
            $table->date('loan_creation_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('rate_of_interest')->nullable();
            $table->string('loan_trioner')->nullable();
            $table->string('grace_period')->nullable();
            $table->string('number_of_instalments')->nullable();
            $table->string('per_year_instalments')->nullable();
            $table->string('size_of_instalments')->nullable();
            $table->unsignedInteger('authorized_by')->nullable();
            $table->date('authorize_date')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
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
        Schema::dropIfExists('bf_loan_accounts');
    }
}
