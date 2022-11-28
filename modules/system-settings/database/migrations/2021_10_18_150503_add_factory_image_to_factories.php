<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFactoryImageToFactories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('factories', function (Blueprint $table) {
            if (!Schema::hasColumn('factories', 'factory_image')) {
                $table->string('factory_image', 255)->nullable()->after('phone_no');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('factories', function (Blueprint $table) {
            if (Schema::hasColumn('factories', 'factory_image')) {
                $table->dropColumn('factory_image');
            }

        });
    }
}
