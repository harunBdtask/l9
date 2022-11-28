<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMultipleColumnsInCuttingTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cutting_targets', function (Blueprint $table) {
            $table->unsignedBigInteger('garments_item_group_id')->nullable()->after('cutting_table_id');
            $table->unsignedBigInteger('garments_item_id')->nullable()->after('garments_item_group_id');
            $table->tinyInteger('is_manual')->nullable()->comment("0=Manual,1=Auto")->after('garments_item_id');
            $table->integer('total_working_minutes')->nullable()->after('wh');
            $table->string('smv', 40)->nullable()->after('total_working_minutes');
            $table->string('req_efficiency', 40)->nullable()->after('smv');
            $table->integer('hourly_target')->nullable()->after('target');
            $table->string('remarks')->nullable()->after('factory_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cutting_targets', function (Blueprint $table) {
            $table->dropColumns([
                'garments_item_group_id',
                'garments_item_id',
                'is_manual',
                'total_working_minutes',
                'smv',
                'req_efficiency',
                'hourly_target',
                'remarks',
            ]);
        });
    }
}
