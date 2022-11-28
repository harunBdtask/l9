<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerBillEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_bill_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('group_id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('project_id');
            $table->unsignedInteger('currency_id')->nullable();
            $table->unsignedInteger('customer_id')->nullable();
            $table->tinyInteger('bill_basis')->default(1)->comment('1=Independent, 2=GIN');
            $table->date('bill_date');
            $table->string('bill_no')->nullable();
            $table->json('details')->nullable();
            $table->string('gin_no')->nullable();
            $table->date('gin_date')->nullable();
            $table->string('cons_rate')->nullable();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('customer_bill_entries');
    }
}
