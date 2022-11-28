<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOutboundBuyerNameToFabricIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fabric_issues', function (Blueprint $table) {
            $table->string('outbound_buyer_name')->nullable()->after('status');
            $table->string('vehicle')->nullable()->after('status');
            $table->string('lock_no')->nullable()->after('status');
            $table->string('driver_name')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fabric_issues', function (Blueprint $table) {
            $table->dropColumn([
                'outbound_buyer_name',
                'vehicle',
                'lock_no',
                'driver_name'
            ]);
        });
    }
}
