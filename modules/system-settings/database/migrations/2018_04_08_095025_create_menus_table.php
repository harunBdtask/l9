<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('menu_name', 191);
            $table->string('menu_url', 191);
            $table->tinyInteger('sort')->nullable();
            $table->unsignedInteger('module_id');
            $table->unsignedInteger('factory_id');
            $table->unsignedInteger('submodule_id')->nullable();
            $table->integer('left_menu')->default(1)->comment = "1=Yes,2=No";
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
