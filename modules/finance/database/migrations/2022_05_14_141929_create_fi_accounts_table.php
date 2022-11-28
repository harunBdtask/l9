<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFiAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fi_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('particulars')->nullable();
            $table->unsignedInteger('type_id')->comment('1 => Assets; 2 => Liabilities; 3 => Equity; 4 => Revenues; 5 => Expenses;');
            $table->unsignedBigInteger('account_type')->comment('1=Parent,2=Group,3=Control,4=Ledger,5=SubLedger');
            $table->unsignedInteger('parent_ac')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
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
        Schema::dropIfExists('fi_accounts');
    }
}
