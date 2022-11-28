<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRemarksToTextileOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('textile_orders', function (Blueprint $table) {
            $table->text('remarks')->nullable()->after('payment_basis');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('textile_orders', function (Blueprint $table) {
            $table->dropColumn('remarks');
        });
    }
}
