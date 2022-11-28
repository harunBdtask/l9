<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('particulars')->nullable();
            $table->unsignedInteger('type_id')->comment('1 => Assets; 2 => Liabilities; 3 => Equity; 4 => Operating Revenues; 5 => Non-operating Revenues & Gains; 6 => Operating Expenses; 7 => Non-operating Expenses & Losses;');
            $table->unsignedInteger('parent_ac')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
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
        Schema::dropIfExists('accounts');
    }
}
