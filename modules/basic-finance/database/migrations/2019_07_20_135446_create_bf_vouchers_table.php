<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBfVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bf_vouchers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('type_id');
            $table->date('trn_date');
            $table->string('file_no')->nullable();
            $table->double('amount');
            $table->string('general_particulars')->nullable();
            $table->json('details');
            $table->unsignedInteger('status_id')->default(0)
                ->comment('0 => Created; 1 => Checked; 2 => Authorized; 3 => Posted; 4 => Amend; 5 => Canceled');
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('factory_id')->default(0)->comment('0 for head office');
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
        Schema::dropIfExists('bf_vouchers');
    }
}
