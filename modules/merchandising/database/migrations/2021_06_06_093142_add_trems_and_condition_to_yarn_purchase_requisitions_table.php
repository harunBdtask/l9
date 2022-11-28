<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTremsAndConditionToYarnPurchaseRequisitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yarn_purchase_requisitions', function (Blueprint $table) {
            $table->json('terms_condition')->after('unapproved_request')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('yarn_purchase_requisitions', function (Blueprint $table) {
            $table->dropColumn('terms_condition');
        });
    }
}
