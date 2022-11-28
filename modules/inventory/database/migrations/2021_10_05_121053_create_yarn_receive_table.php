<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYarnReceiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yarn_receives', function (Blueprint $table) {
            $table->id();

            $table->string('receive_no')->nullable();

            $table->string('receive_purpose');
            $table->unsignedInteger('factory_id');
            $table->string('receive_basis')->comment('WO/PI/Independent');
            $table->string('receive_basis_id')->nullable()->comment('WO/PI id')->nullable();
            $table->string('receive_basis_no')->nullable()->comment('WO/PI No')->nullable();

            $table->unsignedInteger('store_id');

            $table->string('source');
            $table->string('challan_no');
            $table->date('receive_date');
            $table->unsignedInteger('currency_id');
            $table->string('exchange_rate', 20);

            $table->unsignedInteger('loan_party_id')->nullable();
            $table->string('issue_challan_no')->nullable();
            $table->string('lc_no')->nullable();
            $table->text('remarks')->nullable();

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
        Schema::dropIfExists('yarn_receives');
    }
}
