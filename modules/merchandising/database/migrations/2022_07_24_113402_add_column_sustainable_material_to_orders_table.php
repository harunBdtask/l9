<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnSustainableMaterialToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('sustainable_material')
                ->nullable()
                ->comment("1 => GOTS, 2 => OCS, 3 => RCS/GRS, 4 => OEKOTEX, 5 => BCI, 6 => CONVENTIONAL (Default selection).");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['sustainable_material']);
        });
    }
}
