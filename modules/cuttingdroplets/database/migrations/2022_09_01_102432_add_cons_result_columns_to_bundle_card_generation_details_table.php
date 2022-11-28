<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConsResultColumnsToBundleCardGenerationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bundle_card_generation_details', function (Blueprint $table) {
            $table->tinyInteger('cons_result')
                ->comment('0:fail,1:pass')
                ->nullable();

            $table->tinyInteger('is_cons_approved')
                ->comment('0:fail,1:pass')
                ->nullable();
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
            $table->dropColumn(['cons_result', 'is_cons_approved']);
        });
    }
}
