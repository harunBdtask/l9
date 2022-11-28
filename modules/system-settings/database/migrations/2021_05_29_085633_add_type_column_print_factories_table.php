<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeColumnPrintFactoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `print_factories` ADD `factory_type` ENUM('print','wash','knitting') NOT NULL DEFAULT 'print' AFTER `phone_no`;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('print_factories', function (Blueprint $table) {
            $table->dropColumn('factory_type');
        });
    }
}
