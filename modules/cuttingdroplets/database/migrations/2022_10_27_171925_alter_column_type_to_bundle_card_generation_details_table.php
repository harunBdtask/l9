<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnTypeToBundleCardGenerationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bundle_card_generation_details', function (Blueprint $table) {
            $table->boolean('is_cons_approved')
                ->comment('0:fail,1:pass')
                ->default(0)
                ->nullable()
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bundle_card_generation_details', function (Blueprint $table) {
            $table->boolean('is_cons_approved')
                ->comment('0:fail,1:pass')
                ->nullable()
                ->change();
        });
    }
}
