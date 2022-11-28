<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->string('job_no');
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('buyer_id');
            $table->string('quotation_id');
            $table->string('style_name');
            $table->string('style_desc')->nullable();
            $table->string('job_qty')->nullable();
            $table->unsignedInteger('product_department_id')->nullable();
            $table->unsignedInteger('order_uom_id')->nullable();
            $table->unsignedBigInteger('incoterm_id')->nullable();
            $table->string('incoterm_place')->nullable();
            $table->unsignedBigInteger('buying_agent_id')->nullable();
            $table->string('costing_date')->nullable();
            $table->string('costing_per')->nullable();
            $table->string('region')->nullable();
            $table->string('machine_line')->nullable();
            $table->unsignedInteger('currency_id')->nullable();
            $table->string('sewing_smv')->nullable();
            $table->string('cut_efficiency')->nullable();
            $table->string('budget_minute')->nullable();
            $table->string('prod_line_hr')->nullable();
            $table->string('sew_efficiency')->nullable();
            $table->string('cut_smv')->nullable();
            $table->string('copy_from')->nullable();
            $table->unsignedBigInteger('copy_from_id')->nullable()->comment('Order Id (if Copied)');
            $table->string('file_no')->nullable();
            $table->string('internal_ref')->nullable();
            $table->string('image')->nullable();
            $table->string('approve_status', 10)->nullable();
            $table->string('remarks')->nullable();
            $table->string('file')->nullable();
            $table->string('ready_to_approved', 10)->nullable();
            $table->text('un_approve_request')->nullable();
            $table->json('costing')->nullable();

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
        Schema::dropIfExists('budgets');
    }
}
