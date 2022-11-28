<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFundRequisitionAuditApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fund_requisition_audit_approvals', function (Blueprint $table) {
            $table->id();
            $table->date('audit_date');
            $table->unsignedBigInteger('requisition_id');
            $table->unsignedBigInteger('detail_id');
            $table->text('comment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fund_requisition_audit_approvals');
    }
}
