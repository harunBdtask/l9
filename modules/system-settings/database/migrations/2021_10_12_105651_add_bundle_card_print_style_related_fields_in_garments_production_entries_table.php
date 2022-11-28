<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBundleCardPrintStyleRelatedFieldsInGarmentsProductionEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('garments_production_entries', function (Blueprint $table) {
            $table->tinyInteger('bundle_card_print_style')->after('bundle_card_suffix_style')->default(0)->comment("0=Page,1=Sticker");
            $table->string('bundle_card_sticker_width', 20)->after('bundle_card_print_style')->nullable();
            $table->string('bundle_card_sticker_height', 20)->after('bundle_card_sticker_width')->nullable();
            $table->string('bundle_card_sticker_font_size', 20)->after('bundle_card_sticker_height')->nullable();
            $table->string('bundle_card_sticker_max_width', 20)->after('bundle_card_sticker_font_size')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('garments_production_entries', function (Blueprint $table) {
            $table->dropColumn([
                'bundle_card_print_style',
                'bundle_card_sticker_width',
                'bundle_card_sticker_height',
                'bundle_card_sticker_font_size',
                'bundle_card_sticker_max_width',
            ]);
        });
    }
}
