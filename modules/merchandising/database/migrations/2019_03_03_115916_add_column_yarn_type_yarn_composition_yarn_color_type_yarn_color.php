<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnYarnTypeYarnCompositionYarnColorTypeYarnColor extends Migration
{
    public function up()
    {
        Schema::table('budget_yarn_components', function (Blueprint $table) {
            $table->renameColumn('yarn_composition', 'yarn_fabric_composition');
        });
        Schema::table('budget_yarn_components', function (Blueprint $table) {
            $table->unsignedInteger('yarn_type');
            $table->unsignedInteger('yarn_composition');
            $table->unsignedInteger('yarn_color_type');
            $table->unsignedInteger('yarn_color')->nullable();
        });
    }

    public function down()
    {
        // Schema::table('budget_yarn_components', function (Blueprint $table) {
        //     $table->dropColumn('yarn_type');
        //     $table->dropColumn('yarn_composition');
        //     $table->dropColumn('yarn_color_type');
        //     $table->dropColumn('yarn_color');
        // });
    }
}
