<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRackIdColumnIntoGsInvVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gs_inv_vouchers', function (Blueprint $table) {
            $table->foreignId("rack_id")->nullable()->constrained("gs_racks");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gs_inv_vouchers', function (Blueprint $table) {
            $table->dropForeign('gs_inv_vouchers_rack_id_foreign');
            $table->dropColumn("rack_id");
        });
    }
}
