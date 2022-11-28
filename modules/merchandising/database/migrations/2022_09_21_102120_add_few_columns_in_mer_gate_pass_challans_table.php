<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFewColumnsInMerGatePassChallansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mer_gate_pass_challans', function (Blueprint $table) {
            $table->string('party_attn')->after('remarks')->nullable();
            $table->string('party_contact_no')->after('party_attn')->nullable();
            $table->string('supplier_email_address')->after('party_contact_no')->nullable();
            $table->string('supplier_address')->after('supplier_email_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mer_gate_pass_challans', function (Blueprint $table) {
            $table->dropColumn('party_attn');
            $table->dropColumn('party_contact_no');
            $table->dropColumn('supplier_email_address');
            $table->dropColumn('supplier_address');
        });
    }
}
