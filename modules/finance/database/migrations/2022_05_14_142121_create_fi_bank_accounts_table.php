<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFiBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fi_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('factory_id')->nullable();
            $table->unsignedInteger('bank_id')->nullable();
            $table->string('account_number', 40)->nullable();
            $table->unsignedInteger('account_type_id')->nullable();
            $table->date('date')->nullable();
            $table->unsignedInteger('project_id')->nullable();
            $table->unsignedInteger('unit_id')->nullable();
            $table->unsignedInteger('control_account_id')->nullable();
            $table->unsignedInteger('ledger_account_id')->nullable();
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
        Schema::dropIfExists('fi_bank_accounts');
    }
}
